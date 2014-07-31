<?php

namespace Craft\Remote\MailServer;

class Mail extends Part
{

    const TYPE_TEXT = 0;
    const TYPE_MULTIPART = 1;
    const TYPE_MESSAGE = 2;
    const TYPE_APPLICATION = 3;
    const TYPE_AUDIO = 4;
    const TYPE_IMAGE = 5;
    const TYPE_VIDEO = 6;
    const TYPE_OTHER = 7;

    const ENCODING_7BIT = 0;
    const ENCODING_8BIT = 1;
    const ENCODING_BINARY = 2;
    const ENCODING_BASE64 = 3;
    const ENCODING_QUOTED = 4;
    const ENCODING_OTHER = 5;

    /** @var string */
    public $no;

    /** @var string */
    public $uid;

    /** @var string */
    public $date;

    /** @var string */
    public $subject;

    /** @var string */
    public $from;

    /** @var string */
    public $to;

    /** @var string */
    public $cc;

    /** @var string */
    public $header;

    /** @var string */
    public $sender;

    /** @var Part[] */
    public $parts = [];

    /** @var Attachment[] */
    public $attachments = [];


    /**
     * Open mail
     * @param resource $imap
     * @param string $uid
     */
    public function __construct($imap, $uid)
    {
        $this->imap = $imap;
        $this->uid = $uid;
    }


    /**
     * Get types body
     * @param string $type
     * @return string
     */
    public function body($type = 'HTML')
    {
        // generate typed body
        $body = null;

        // from all parts
        if($this->multipart) {
            foreach($this->parts as $part) {
                if($part->subtype === $type) {
                    $body .= $part->body;
                }
            }
        }
        // simple part
        elseif($this->subtype === $type) {
            $body = $this->body;
        }

        return $body;
    }


    /**
     * Print mail
     * @return string
     */
    public function __toString()
    {
        return $this->body;
    }


    /**
     * Flag message
     * @param string $flag
     * @return $this
     */
    public function flag($flag)
    {
        $flag = '\\' . ucfirst(strtolower(trim($flag, '\\')));
        imap_setflag_full($this->imap, $this->uid, $flag, ST_UID);
        return $this;
    }


    /**
     * Unflag message
     * @param string $flag
     * @return $this
     */
    public function unflag($flag)
    {
        $flag = '\\' . ucfirst(strtolower(trim($flag, '\\')));
        imap_clearflag_full($this->imap, $this->uid, $flag, ST_UID);
        return $this;
    }


    /**
     * Delete message
     * @return bool
     */
    public function delete()
    {
        return imap_delete($this->imap, $this->uid, FT_UID);
    }


    /**
     * Generate mail
     * @param resource $imap
     * @param string $uid
     * @throws Exception\MailNotFound
     * @return Mail
     */
    public static function make($imap, $uid)
    {
        // get overview
        $overview = imap_fetch_overview($imap, $uid, FT_UID);

        // mail does not exist
        if(!$overview = array_shift($overview)) {
            throw new Exception\MailNotFound('Mail #' . $uid . ' does not exist');
        }

        // create mail object
        $mail = new Mail($imap, $uid);

        // set basic data
        $mail->subject = $overview->subject;
        $mail->from = $overview->from;
        $mail->to = $overview->to;
        $mail->date = $overview->date;
        $mail->no = $overview->msgno;

        $mail->recent = (bool)$overview->recent;
        $mail->answered = (bool)$overview->answered;
        $mail->deleted = (bool)$overview->deleted;
        $mail->seen = (bool)$overview->seen;
        $mail->draft = (bool)$overview->draft;

        // get header
        $raw = imap_fetchheader($imap, $uid, FT_UID);
        $header = imap_rfc822_parse_headers($raw);
        $mail->header= $raw;

        if(isset($header->ccaddress)) {
            $mail->cc = $header->ccaddress;
        }

        $mail->sender = $header->senderaddress;

        // get structure
        $struct = imap_fetchstructure($imap, $uid, FT_UID);

        // get type
        $mail->type = (int)$struct->type;
        $mail->encoding = (int)$struct->encoding;
        $mail->multipart = ($mail->type === self::TYPE_MULTIPART);

        if($struct->ifsubtype) {
            $mail->subtype = $struct->subtype;
        }

        // get params
        if($struct->ifparameters) {
            foreach($struct->parameters as $param) {
                $mail->params[strtolower($param->attribute)] = $param->value;
            }
        }
        if($struct->ifdparameters) {
            foreach($struct->dparameters as $param) {
                $mail->params[strtolower($param->attribute)] = $param->value;
            }
        }

        // parse parts
        if($mail->multipart) {

            // start deep parsing
            foreach($struct->parts as $no => $part) {
                $mail = static::parse($imap, $mail, $no + 1, $no + 1, $part);
            }

            // generate body
            $mail->body = $mail->body();
        }
        else {
            $body = imap_body($imap, $uid, FT_UID);
            $mail->body = static::decode($body, $mail->encoding);
        }

        return $mail;
    }


    /**
     * Parse sub part
     * @param resource $imap
     * @param Mail $mail
     * @param string $no
     * @param string $label
     * @param \stdClass $struct
     * @return Mail
     */
    protected static function parse($imap, Mail $mail, $no, $label, \stdClass $struct)
    {
        // attachment or sub-part ?
        $part = ($struct->ifdisposition && $struct->disposition == 'attachment')
            ? new Attachment($imap)
            : new Part($imap);

        $part->type = (int)$struct->type;
        $part->encoding = (int)$struct->encoding;
        $part->multipart = ($part->type === self::TYPE_MULTIPART);

        if($struct->ifsubtype) {
            $part->subtype = $struct->subtype;
        }

        // get params
        if($struct->ifparameters) {
            foreach($struct->parameters as $param) {
                $part->params[strtolower($param->attribute)] = $param->value;
            }
        }
        if($struct->ifdparameters) {
            foreach($struct->dparameters as $param) {
                $part->params[strtolower($param->attribute)] = $param->value;
            }
        }

        // is attachment
        if($part instanceof Attachment) {

            // set filename
            $part->filename = $part->params['filename'];

            // parse & clean body
            $body = imap_fetchbody($imap, $mail->uid, $label, FT_UID);
            $part->body = static::decode($body, $part->encoding);

            // add attachment
            $key = uniqid() . '-' . $part->params['filename'];
            $mail->attachments[$key] = $part;

        }
        // is multi parts
        elseif($part instanceof Part and $part->multipart) {

            // add part
            $mail->parts[(string)$label] = $part;

            // parse sub-parts
            foreach($struct->parts as $nno => $nstruct) {
                $nno++;
                $mail = static::parse($imap, $mail, $nno, $label . '.' . $nno, $nstruct);
            }

        }
        // is simple part
        elseif($part instanceof Part) {

            // parse & clean body
            $body = imap_fetchbody($imap, $mail->uid, $label, FT_UID);
            $part->body = static::decode($body, $part->encoding);

            // add part
            $mail->parts[(string)$label] = $part;

        }

        return $mail;
    }


    /**
     * Decode body
     * @param string $body
     * @param int $encoding
     * @return string
     */
    protected static function decode($body, $encoding)
    {
        if($encoding === self::ENCODING_8BIT) {
            $body = imap_8bit($body);
        }
        elseif($encoding === self::ENCODING_BINARY) {
            $body = imap_binary($body);
        }
        elseif($encoding === self::ENCODING_BASE64) {
            $body = imap_base64($body);
        }
        elseif($encoding === self::ENCODING_QUOTED) {
            $body = imap_qprint($body);
        }

        return $body;
    }

} 