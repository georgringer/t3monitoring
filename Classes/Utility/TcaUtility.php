<?php
namespace T3Monitor\T3monitoring\Utility;

class TcaUtility
{
    public function getCheckTypes($config)
    {
        foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3monitoring']['resolver'] as $name => $class) {
            $config['items'][] = [
                $class::TITLE,
                $name
            ];
        }
        return $config;
    }
}