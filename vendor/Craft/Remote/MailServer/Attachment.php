<?php

namespace Craft\Remote\MailServer;

class Attachment extends Part
{

    /** @var string */
    public $filename;


    /**
     * Download attachment
     * @param string $to
     * @param bool $force
     * @return int
     */
    public function download($to, $force = false)
    {
        // clean path
        $to = rtrim($to, '/\\') . DIRECTORY_SEPARATOR . $this->filename;

        // already exists
        if(!$force and file_exists($to)) {
            $to = rtrim($to, '/\\') . DIRECTORY_SEPARATOR . uniqid() . '-' . $this->filename;
        }

        return file_put_contents($to, $this->body);
    }

}