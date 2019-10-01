<?php namespace Standardizer;

/**
 * Defines the base methods for a converter
 */
class Converter
{
    // Standard configuration
    private $standard;
    private $config;
    private $output;

    /**
     * Class constructor.
     */
    public function __construct(string $inputFile, array $config = [])
    {
        $this->config = $config;
        $this->standard = $this->loadStandardConfig();
        // Create file for converted output
        //        $this->output = fopen($outputFolder.basename($outputFile), "w");
    }

    /**
     * Load standard configuration based on current classname
     *
     * @return array
     */
    private function loadStandardConfig() : array
    {
        if (get_parent_class($this) == '') {
            return [];
        }
        $classname = (new \ReflectionClass($this))->getShortName();
        $class = str_replace('Converter', '', $classname);
        return config('standards')->get(strtolower($class));
    }

    /**
     * Return the standard configuration array
     *
     * @return array
     */
    public function getStandard() : array
    {
        return $this->standard;
    }

    

    //Filesystem::getLines($rawFilePath);
}
