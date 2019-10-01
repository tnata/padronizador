<?php

require_once('vendor/autoload.php');

use Configula\ConfigFactory as Config;

function config($file) {
    return Config::loadPath('config/local/'.$file.'.php');
}