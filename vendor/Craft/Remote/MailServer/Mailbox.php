<?php

namespace Craft\Remote\MailServer;

class Mailbox
{

    /** @var resource */
    protected $imap;


    /**
     * Open folder
     * @param resource $imap
     */
    public function __construct($imap)
    {
        $this->imap = $imap;
    }

    /**
     * Get mails
     * @param string $criteria
     * @return Mail[]
     */
    public function mails($criteria = 'ALL')
    {
        // fetch mails
        $mails = imap_search($this->imap, $criteria, SE_UID);

        // no mail
        if(!$mails) {
            return [];
        }

        // cast as Mail
        foreach($mails as $key => $uid) {
            $mails[$key] = $this->mail($uid);
        }

        return $mails;
    }


    /**
     * Get mail
     * @param string $uid
     * @return Mail
     */
    public function mail($uid)
    {
        return Mail::make($this->imap, $uid);
    }


    /**
     * Clear deleted mails
     * @return bool
     */
    public function clear()
    {
        return imap_expunge($this->imap);
    }


    /**
     * Close imap
     */
    public function __destruct()
    {
        imap_close($this->imap);
    }

} 