<?php

namespace Src\Helper\Logger\impl;

use Src\Helper\Logger\ILogger;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MyLogger implements ILogger
{
    private static ?MyLogger $instance = null;
    private Logger $errorLogger;
    private Logger $warningLogger;
    private Logger $debugLogger;
    private Logger $infoLogger;

    public function __construct()
    {
        // create a logger instance for errors
        $this->errorLogger = new Logger('error');
        $this->errorLogger->pushHandler(new StreamHandler(LOGS_FOLDER . '/error.log', Logger::ERROR));

        // create a logger instance for warnings
        $this->warningLogger = new Logger('warning');
        $this->warningLogger->pushHandler(new StreamHandler(LOGS_FOLDER . '/warning.log', Logger::WARNING));

        // create a logger instance for debug messages
        $this->debugLogger = new Logger('debug');
        $this->debugLogger->pushHandler(new StreamHandler(LOGS_FOLDER . '/debug.log', Logger::DEBUG));

        // create a logger instance for info messages
        $this->infoLogger = new Logger('info');
        $this->infoLogger->pushHandler(new StreamHandler(LOGS_FOLDER . '/info.log', Logger::INFO));
    }

    public static function getInstance(): ILogger //singleton
    {
        if (!self::$instance) {
            self::$instance = new MyLogger();
        }
        return self::$instance;
    }

    public function error($message): void
    {
        $this->errorLogger->error($message);
    }

    public function warning($message): void
    {
        $this->warningLogger->warning($message);
    }

    public function debug($message): void
    {
        $this->debugLogger->debug($message);
    }

    public function info($message): void
    {
        $this->infoLogger->info($message);
    }
}