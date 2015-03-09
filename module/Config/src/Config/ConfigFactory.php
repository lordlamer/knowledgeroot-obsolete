<?php

namespace Config;

class ConfigFactory {

    public static function create($configFile) {
        // init config
        $configReader = new \Zend\Config\Reader\Ini();
        $configData = $configReader->fromFile($configFile);
        return new \Zend\Config\Config($configData, true);
    }

}
