<?php

use Configula\ConfigFactory as Config;

function config($key) {
    return Config::loadSingleDirectory('config')[$key];
}