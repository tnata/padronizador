<?php
 
// Load PhpSpreadsheet library.
require_once('bootstrap.php');

// Local classes
use Standardizer\Filesystem;
use Standardizer\Factories;

use Standardizer\Factories\ExporterFactory;
use Standardizer\Factories\ConverterFactory;


// Get the array of files from cmd input folder
$inputFiles = Filesystem::scanAllDir(config('cmd')->get('input_folder'));

// Read the input folder files and convert them
foreach($inputFiles as $inputFile) {
    try {
        // Create new exporter instance
        $exporter = ExporterFactory::create(
            config('cmd')->get('input_folder').$inputFile
        );

        // Create the conversor instance
        $converter = ConverterFactory::create($exporter);

    } catch (\Exception $e) {
        // Advance to the next file
        continue;
    }

    $lines = explode(PHP_EOL, $csvFileContent);
    foreach($lines as $lnum => $line)
    {

            // START CADASTRO PARSER
            if ($standardName == 'cadastro') {
                
            }
            // END CADASTRO PARSER

            // COBRANCA PARSER
            if ($standardName == 'cobranca') {
                if ($mode == 'inadimplencia') {
                    if ($lineToParse[1] != '""') {
                        $prevUnidade = $parsedLine['unidade'] = $lineToParse[1];
                    } else {
                        $parsedLine['unidade'] = $prevUnidade;
                    }

                    if ($lineToParse[0] != '""') {
                        $prevBloco = $parsedLine['bloco'] = $lineToParse[0];
                    } else {
                        $parsedLine['bloco'] = $prevBloco;
                    }

                    // Prevents zeroed for 'bloco'
                    $parsedLine['bloco'] = ($parsedLine['bloco'] == 0) ? '' : $parsedLine['bloco'];

                    $parsedLine['vencimento'] = $lineToParse[8];
                    $parsedLine['nosso_numero'] = $lineToParse[7];
                    // $parsedLine['[RECEITA_APROPRIACAO][0][conta_categoria]'] = ??;
                    // $parsedLine['[RECEITA_APROPRIACAO][0][complemento]'] = ??;
                    $parsedLine['[RECEITA_APROPRIACAO][0][valor]'] = str_replace('??', '', @$lineToParse[11]);
                }

                if ($mode == 'acordo') {
                    $blocoEunidade = explode('-', str_replace('Bl/Unidade:', '', $lineToParse[0]))[0];
                    $parsedLine['unidade'] = explode('/', $blocoEunidade)[1];
                    $parsedLine['bloco'] = explode('/', $blocoEunidade)[0];

                    $parsedLine['vencimento'] = $lineToParse[18];
                    $parsedLine['nosso_numero'] = $lineToParse[21];
                    // $parsedLine['[RECEITA_APROPRIACAO][0][conta_categoria]'] = ??;
                    // $parsedLine['[RECEITA_APROPRIACAO][0][complemento]'] = ??;
                    $parsedLine['[RECEITA_APROPRIACAO][0][valor]'] = str_replace('??', '', @$lineToParse[25]);
                }
            }
            // END COBRANCA PARSER

            // Extra steps for every case
            foreach($parsedLine as $key => $value) {
                // Remove extra commas on all values
                $value = str_replace('"', '', $value);
                // Trim all values both sides
                $value = trim($value);
                // Update original array
                $parsedLine[$key] = $value;
            }

            //Implode line to string again
            $implodedLine = implode(',', $parsedLine);

            // Check for end line string
            if (isset($standard['end_file_string'])) {
                // Only for inadimplencia mode
                if ($mode == 'inadimplencia') {
                    // Finalize file processing when string found
                    if (Str::create($implodedLine)->contains($standard['end_file_string'])) {
                        //Set cutBottom to current line number
                        $cutBottom = $fileLines - $lnum;
                        continue;
                    }
                }
            }

            // Write line to file
            fwrite($outputConverted, PHP_EOL.$implodedLine);

            // Reset line to write
            $toWrite = '';
            // Reset parsed line
            $parsedLine = [];
        }
    }

    fclose($outputConverted);

}
