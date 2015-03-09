<?php

namespace Doctrine;

class DoctrineFactory {

    public static function create($dsn) {
        $doctrineConfig = new \Doctrine\DBAL\Configuration();

        $connectionParams = array(
            'url' => $dsn,
        );

        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $doctrineConfig);

        return $conn;
    }

}
