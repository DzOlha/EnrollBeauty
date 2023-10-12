<?php

namespace Src\Helper\Logger;

interface ILogger
{
    public static function getInstance(): ILogger;
    public function error($message): void;

    public function warning($message): void;

    public function debug($message): void;

    public function info($message): void;
}