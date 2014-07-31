<?php

require 'vendor/autoload.php';
require 'config.php';

use Craft\Remote\MailServer;

// use imap
if(HC_IMAP) {

    // open server
    $server = new MailServer(HC_IMAP_HOST, HC_IMAP_USERNAME, HC_IMAP_PASSWORD);
    $mailbox = $server->in('INBOX');

    // check all mails
    $count = 0;
    foreach($mailbox->mails('UNSEEN') as $mail) {

        // download attachments
        foreach($mail->attachments as $attachment) {
            $valid &= $attachment->download(HC_ROOT . HC_IMAP_DIR);
            $count++;
        }

    }

    die((string)$count);

}