<?php

namespace Craft\Remote;

class MailServer
{

    /** @var string */
    protected $ref;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var resource */
    protected $imap;


    /**
     * Open mailbox
     * @param string $ref
     * @param string $username
     * @param string $password
     */
    public function __construct($ref, $username, $password)
    {
        $this->ref = '{' . trim($ref, '{}') . '}';
        $this->username = $username;
        $this->password = $password;
        $this->imap = imap_open($this->ref, $username, $password);
    }


    /**
     * List folders
     * @param string $search
     * @return array
     */
    public function folders($search = '*')
    {
        return imap_getmailboxes($this->imap, $this->ref, $search);
    }


    /**
     * Open folder
     * @param string $folder
     * @return MailServer\Mailbox
     */
    public function in($folder)
    {
        $folder = str_replace($this->ref, null, $folder);
        $imap = imap_open($this->ref . $folder, $this->username, $this->password);
        return new MailServer\Mailbox($imap);
    }


    /**
     * Close imap
     */
    public function __destruct()
    {
        imap_close($this->imap);
    }

} 