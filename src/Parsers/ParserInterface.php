<?php namespace Standardizer\Interfaces;

interface Parser {
    public function setOutput($name, $var);
    public function getTranslateLine($name, $var);
}