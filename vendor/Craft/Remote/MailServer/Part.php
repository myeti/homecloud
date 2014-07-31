<?php

namespace Craft\Remote\MailServer;

class Part
{

    /** @var int */
    public $encoding;

    /** @var int */
    public $type;

    /** @var string */
    public $subtype;

    /** @var bool */
    public $multipart;

    /** @var array */
    public $params = [];

    /** @var string */
    public $body;

    /** @var resource */
    protected $imap;


    /**
     * Create part
     * @param resource $imap
     */
    public function __construct($imap)
    {
        $this->imap = $imap;
    }

}