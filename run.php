<?php
 
// Load PhpSpreadsheet library.
require_once('vendor/autoload.php');
require_once('bootstrap.php');
 
// Import classes.
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Stringy\Stringy as Str;

//TODO: Put in config file
//Define the main folder for execution
$inputFolder = 'input/';
$outputFolder = 'output/';
$outputRaw = $outputFolder.'raw/';

function scanAllDir($dir) {
    $result = [];
    foreach(scandir($dir) as $filename) {
        if ($filename[0] === '.') continue;
        $filePath = $dir . '/' . $filename;
        if (is_dir($filePath)) {
            foreach (scanAllDir($filePath) as $childFilename) {
                $result[] = $filename . '/' . $childFilename;
            }
        } else {
            $result[] = $filename;
        }
    }
    return $result;
}

function countLines($file) {
    $linecount = 0;
    $handle = fopen($file, "r");
    while(!feof($handle)){
        fgets($handle); //Needed to advance the file pointer
        $linecount++;
    }
    fclose($handle);
    return $linecount;
}
 
// Read the input folder files and process it
foreach(scanAllDir($inputFolder) as $inputFile) {
    //VALIDATIONS
    // Ignore main folders
    if (in_array($inputFile, ['.', '..'])) {
        continue;
    }

    // Work only in files with disired extension
    $allowedExtensions = ['.xls', '.xlsx'];
    if (!Str::create($inputFile)->containsAny($allowedExtensions)) {
        continue;
    }

    // Create the pathinfo element
    $inputFileInfo = pathinfo($inputFolder.$inputFile);

    //READER FACTORY
    //Determine the reader format by the file extension
    switch ($inputFileInfo['extension']) {
        case 'xls':
            $readerFormat = "Xls";
            break;
        default:
            $readerFormat = "Xlsx";
            break;
    }
    // Read the file using PhpSpreadsheet
    $reader = IOFactory::createReader($readerFormat);

    //EXPORTER
    $spreadsheet = $reader->load($inputFolder.$inputFile);
    
    // Export to CSV in output
    $writer = IOFactory::createWriter($spreadsheet, "Csv")
    ->setSheetIndex(0)   // Select which sheet to export.
    ->setDelimiter(';.;');  // Set a crazy delimiter.
    
    $outputFile = str_replace($inputFileInfo['extension'], 'csv', $inputFile);
    // Save raw conversion output
    $rawFilePath = $outputRaw.basename($outputFile);
    $writer->save($rawFilePath);

    //CONVERSOR
    // Define the standard for conversion
    $standardName = 'cadastro';
    if (Str::create($inputFile)->contains('cobranca')) {
        $standardName = 'cobranca';
    }

    // Execute file standardization for Superlógica import
    $csvFileContent = fread(fopen($rawFilePath, 'r'), filesize($rawFilePath));

    // Bind standard attributes from config
    $standard = config('standards')[$standardName];

    // Create file for converted output
    $outputConverted = fopen($outputFolder.basename($outputFile), "w");

    //Get the total number of lines in the raw file
    $fileLines = countLines($rawFilePath);

    // Start field or line elimination based on standard 
    if ($standardName == 'cadastro') {
        $cutTop = 3; //Lines to discard at top
        $cutBottom = 1; //Linest o discard at bottom

        $concatEvery = 5; //Concatenate every X lines
        $concatIndex = $cutTop; //Concatenation index starting point

        // Get the headers values
        $fieldsToImplode = $standard['fields'];
    }

    // Start field or line elimination based on standard 
    if ($standardName == 'cobranca') {
        if (Str::create($inputFile)->contains('acordo')) {
            $mode = 'acordo';
            $cutTop = 4; //Lines to discard at top
            $cutBottom = 3; //Linest o discard at bottom
    
            $concatEvery = 2; //Concatenate every X lines
            $concatIndex = $cutTop; //Concatenation index starting point
        }

        if (Str::create($inputFile)->contains('inadimplencia')) {
            $mode = 'inadimplencia';
            $cutTop = 6; //Lines to discard at top
            $cutBottom = 0; //This mode have a end line string
    
            $concatEvery = 1; //This mode need to be parsed line by line
            $concatIndex = $cutTop; //Concatenation index starting point
        }

        // Get the headers values
        $fieldsToImplode = $standard['fields'];
        // Add 18 dynamic fields
        for ($i=0; $i < 18; $i++) { 
            foreach($standard['array_fields'] as $key => $fields) {
                foreach($fields as $field) {
                    $fieldsToImplode[] = "[$key][$i][$field]";
                }
            }
        }
    }

    // Write header to converted output file based on standard
    fwrite($outputConverted, implode(',', $fieldsToImplode));

    // Line concatenation variable
    $toWrite = '';

    $lines = explode(PHP_EOL, $csvFileContent);
    foreach($lines as $lnum => $line)
    {
        // Convert content to ISO-8859-1 compatible
        $line = utf8_decode($line);

        // Corrects the $lnum to start from 1 instead of 0
        $lnum++;

        // Discard N top lines
        if ($lnum <= $cutTop) {
            continue;
        }

        //Discart N last lines
        if ($lnum >= ($fileLines - $cutBottom)) {
            continue;
        }

        // Check for discard lines with unwanted text
        $continue = false;
        foreach($standard['discard'] as $discardText) {
            if (Str::create($line)->contains($discardText)) {
                $continue = true;
            }
        }
        foreach($standard['discard_equals'] as $discardText) {
            if ($line == $discardText) {
                $continue = true;
            }
        }
        if ($continue) {
            //Increase concat index to consider ignored line
            $concatIndex++;
            continue;
        }

        // Concatenate line to write
        $toWrite .= $line;

        // Check for line concatenation moment
        if (($concatIndex+$concatEvery) == $lnum) {
            // Set Current position for concatenation index
            $concatIndex = $lnum;

            //Generate line to parse
            $lineToParse = explode(';.;', $toWrite);

            //Create empty indexed array from config fields to implode
            foreach($fieldsToImplode as $field) {
                $parsedLine[$field] = '';
            }

            // START CADASTRO PARSER
            if ($standardName == 'cadastro') {
                // Start line parsing
                $parsedLine['bloco'] = @explode('/', $lineToParse[1])[0];
                $parsedLine['bloco'] = ($parsedLine['bloco'] == 0) ? '' : $parsedLine['bloco'];
                $parsedLine['unidade'] = @explode('/', $lineToParse[1])[1];
                $parsedLine['proprietario_nome'] = @explode('CPF:', $lineToParse[3])[0];
                $parsedLine['proprietario_cpf/cnpj'] = @explode('CPF:', $lineToParse[3])[1];
                $parsedLine['proprietario_rg'] = @explode('RG:', $lineToParse[8])[1];
                $phones = explode('  ', $lineToParse[13]);
                foreach($phones as $key => $phone) {
                    $phoneCleaned = preg_replace('/[^0-9]/', '', $phone);
                    if ($phoneCleaned == '') {
                        unset($phones[$key]);
                    } else {
                        $phones[$key] = $phoneCleaned;
                    }
                }
                $parsedLine['proprietario_telefone'] = implode(";", $phones);
                $parsedLine['proprietario_email'] = @explode('e-mail:', $lineToParse[18])[1];
                $parsedLine['proprietario_endereco'] = str_replace(',', '', @$lineToParse[22]);
                $address = explode('  ', @$lineToParse[25]);
                $city = @explode(' - ', @$address[0])[0];
                $state = @explode(' - ', @$address[0])[1];

                $parsedLine['proprietario_cep'] = @$address[1];
                $parsedLine['proprietario_cidade'] = $city;
                $parsedLine['proprietario_bairro'] = @$address[2];
                $parsedLine['proprietario_estado'] = $state;
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
                    // $parsedLine['[RECEITA_APROPRIACAO][0][complemento]'] = ??;
                    $parsedLine['[RECEITA_APROPRIACAO][0][valor]'] = str_replace('??', '', @$lineToParse[11]);
                }

                if ($mode == 'acordo') {
                    $blocoEunidade = explode('-', str_replace('Bl/Unidade:', '', $lineToParse[0]))[0];
                    $parsedLine['unidade'] = @explode('/', $blocoEunidade)[1];
                    $parsedLine['bloco'] = @explode('/', $blocoEunidade)[0];

                    $parsedLine['vencimento'] = $lineToParse[18];
                    $parsedLine['nosso_numero'] = $lineToParse[21];
                    // $parsedLine['[RECEITA_APROPRIACAO][0][complemento]'] = ??;
                    $parsedLine['[RECEITA_APROPRIACAO][0][valor]'] = str_replace('??', '', @$lineToParse[25]);
                }
                $parsedLine['[RECEITA_APROPRIACAO][0][conta_categoria]'] = '1.1';
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
