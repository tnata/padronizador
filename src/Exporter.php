<?php namespace Standardizer;

/**
 * Create a new standardizer exporter object
 */
class Exporter
{
    // Properties
    private $inputFilePath;
    private $inputFileInfo;

    private $config;

    /**
     * Class constructor.
     */
    public function __construct(string $inputFilePath)
    {
        // Load config to local object
        $this->config = config('exporter');
        // Input file path
        $this->inputFilePath = $inputFilePath;
        // Get file info from file path
        $this->inputFileInfo = pathinfo($this->inputFilePath);
    }

    /**
     * Run execute the exporter instance and generates output
     *
     * @return string
     */
    public function run() : string
    {
        // Create the reader
        $reader = Factories\ReaderFactory::create(
            $this->inputFileInfo['extension']
        );

        // Load imput file to reader
        $spreadsheet = $reader->load($this->inputFilePath);

        // Create the output file path
        $outputFilePath = str_replace(
            $this->inputFileInfo['extension'],
            $this->config->get('output_type'),
            $this->inputFilePath
        );
        // Create the writer factory instance
        $writer = Factories\WriterFactory::create($spreadsheet, $outputFilePath);

        // Create the raw file path
        $rawFilePath = $this->config->get('raw_folder').basename($outputFilePath);

        // Save raw conversion output
        $writer->save($rawFilePath);

        // Return the generated csv path
        return $rawFilePath;
    }
}
