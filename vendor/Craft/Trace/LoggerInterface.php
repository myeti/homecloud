<?php

namespace Craft\Trace;

use Psr\Log\LoggerInterface as PsrLoggerInterface;

interface LoggerInterface extends PsrLoggerInterface
{

    /**
     * Get all logs
     * @return string
     */
    public function logs();

}