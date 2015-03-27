<?php

namespace Monolog;

class MonologFactory {

    public static function create($logFile, $ident, $logLevel) {
        // init logger
        $logger = new \Monolog\Logger($ident);
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($logFile, $logLevel));

        return $logger;
    }

}
