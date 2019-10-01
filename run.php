<?php
 
// Load PhpSpreadsheet library.
require_once('bootstrap.php');

// Local classes
use Standardizer\Filesystem;
use Standardizer\Factories;

// Get the array of files from cmd input folder
$inputFiles = Filesystem::scanAllDir(config('cmd')->get('input_folder'));

// Read the input folder files and convert them
foreach($inputFiles as $inputFile) {
    try {
        // Create new exporter instance
        $exporter = Factories\ExporterFactory::create(
            config('cmd')->get('input_folder').$inputFile
        );

        // Execute csv conversion
        $rawFilePath = $exporter->run();

        // Create the conversor instance
        $converter = Factories\ConverterFactory::create(
            $inputFile,
            $rawFilePath
        );

    } catch (\Exception $e) {
        // Advance to the next file
        continue;
    }

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
            $lineToParse = explode(',', $toWrite);

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
                $parsedLine['proprietario_endereco'] = @$lineToParse[22];
                $address = explode('  ', @$lineToParse[26]);
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
