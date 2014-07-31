<?php

namespace Forge;

use Craft\Trace\Logger as Writer;
use Craft\Trace\LoggerInterface;

abstract class Logger
{

    /**
     * Get writer instance
     * @param LoggerInterface $writer
     * @return LoggerInterface
     */
    public static function writer(LoggerInterface $writer = null)
    {
        static $instance;
        if($writer) {
            $instance = $writer;
        }
        if(!$instance) {
            $instance = new Writer;
        }

        return $instance;
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function emergency($message, array $context = [])
    {
        static::writer()->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function alert($message, array $context = [])
    {
        static::writer()->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function critical($message, array $context = [])
    {
        static::writer()->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function error($message, array $context = [])
    {
        static::writer()->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function warning($message, array $context = [])
    {
        static::writer()->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function notice($message, array $context = [])
    {
        static::writer()->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function info($message, array $context = [])
    {
        static::writer()->info($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function debug($message, array $context = [])
    {
        static::writer()->debug($message, $context);
    }


    /**
     * Write log
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public static function log($level, $message, array $context = [])
    {
        static::writer()->log($level, $message, $context);
    }


    /**
     * Get all logs
     * @return string
     */
    public static function logs()
    {
        return static::writer()->logs();
    }

}