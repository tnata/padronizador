<?php namespace Standardizer;

/**
 * Class for common filesystem operations
 */
class Filesystem
{
    /**
     * Scan a folder recursively and return an array of elements
     *
     * @param String $dir
     * @return array
     */
    public static function scanAllDir($dir) : array
    {
        $result = [];
        foreach(scandir($dir) as $filename) {
            if ($filename[0] === '.') continue;
            $filePath = $dir . '/' . $filename;
            if (is_dir($filePath)) {
                foreach (self::scanAllDir($filePath) as $childFilename) {
                    $result[] = $filename . '/' . $childFilename;
                }
            } else {
                $result[] = $filename;
            }
        }
        return $result;
    }

    /**
     * Cunts the number of lines in a text file
     *
     * @param String $file
     * @return integer
     */
    public static function countLines($file) : int
    {
        $linecount = 0;
        $handle = fopen($file, "r");
        while(!feof($handle)){
            fgets($handle); //Needed to advance the file pointer
            $linecount++;
        }
        fclose($handle);
        return $linecount;
    }

    /**
     * Get the inputFile lines array
     *
     * @param string $path Caminho do arquivo
     * 
     * @return array
     * @throws \Exception when the file is not found
     **/
    public function getLines(string $path)
    {
        if (!file_exists($path)) {
            throw new \Exception('Arquivo não encontrado!');
        }
        $file = fread(fopen($path, 'r'), filesize($path));
        return explode(PHP_EOL, $file);
    }

    /**
     * Create a file pointer to put contents
     *
     * @param string $path Path for the new file
     * @return resource
     * @throws \Exception when the path is inacessible
     **/
    public function FunctionName(string $path)
    {
        return fopen($path, "w");
    }
}