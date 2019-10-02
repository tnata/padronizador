<?php namespace Standardizer;

use Standardizer\Converter;

/**
 * Document parser class
 */
class Parser
{
    protected $erp;
    protected $mode;
    protected $config;

    protected $converter;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        // Prevent base parser instantiation
        if (get_parent_class($this) == '') {
            throw new \Exception('Classe não instanciável, use uma Factory');
        }

        // Set the erp attribute based on current classname
        $classname = (new \ReflectionClass($this))->getShortName();
        $class = str_replace('Parser', '', $classname);
        $this->setErp(strtolower($class));

        // Set default mode
        $this->setMode('default');
    }

    /**
     * Sets the parser config based on converter type and parser mode
     *
     * @param string $file The file originator
     * @return array
     **/
    public function setConfig(Converter $converter)
    {
        // Define the parser configuration
        $config = config('parsers')->get($this->getErp());

        $this->converter = $converter;

        $this->config = $config[$converter->getType()][$this->mode];
    }

    /**
     * Get parser config
     *
     * @return array
     **/
    public function getConfig()
    {
        return $this->config;
    }
    
    /**
     * Set erp configuration based on current classname
     *
     * @return void
     */
    protected function setErp(string $erp) : void
    {
        //TODO: Implement erp validation
        $this->erp = $erp;
    }


    /**
     * Set mode attribute
     *
     * @return void
     */
    public function setMode(string $mode) : void
    {
        //TODO: Implement mode validation
        //Validate the modes in config array
        // $found = false;
        // foreach($this->config['subtypes'] as $subtype => $values) {
        //     if ($subtype == $mode) {
        //         $found = true;
        //     }
        // }
        // if (!$found) {
        //     throw new \Exception('Modo não permitido!');
        // }
        $this->mode = $mode;
    }
}
