<?php namespace Standardizer;

// Thyrd party lib's
use Stringy\Stringy as Str;

use Standardizer\Filesystem;

/**
 * Defines the base methods for a converter
 */
class Converter
{
    // Standard configuration
    protected $standard;
    protected $config;

    protected $inputFilePath;
    protected $inputFileInfo;

    protected $outputFolder;
    protected $outputFilePath;
    protected $outputFile;

    protected $rawFilePath;

    /**
     * Class constructor.
     */
    public function __construct(string $inputFilePath, string $rawFilePath){
        $this->config = self::getConfig($inputFilePath);
        $this->standard = $this->loadStandardConfig();
        $this->inputFilePath = $inputFilePath;
        $this->rawFilePath = $rawFilePath;

        // Create input file info
        $this->inputFileInfo = Filesystem::getInfo($this->inputFilePath);

        $this->outputFilePath = str_replace(
            $this->inputFileInfo['extension'],
            'csv',
            $this->inputFilePath
        );

        // Get the output folder from converters config
        $this->outputFolder = config('converters')->get('output_folder');
    }

    /**
     * Load standard configuration based on current classname
     *
     * @return array
     */
    protected function loadStandardConfig() : array
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

    /**
     * Return the configuration of a converter based on input file string
     *
     * @param string $file The file originator
     * @return array
     **/
    public static function getConfig(string $file)
    {
        // Define the converter configuration
        foreach(config('converters')->get('available') as $config) {
            if (Str::create($file)->contains($config['name'])) {
                return $config;
            }
        }
    }

    /**
     * Execute the conversor and generates output
     *
     * @return bool
     * @throws conditon
     **/
    public function run() : bool
    {
        // Write header to converted output file based on standard
        Filesystem::writeLine(
            $this->outputFile,
            implode(',', $this->getFieldsToImplode())
        );

        
        return true;
    }
}
