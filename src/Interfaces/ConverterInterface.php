<?php namespace Standardizer\Interfaces;

interface ConverterInterface {
    /**
     * Execute conversion
     *
     * @return bool
     */
    public function run() : bool;

    public function getCutTop() : int; // Lines to discard at top
    public function getCutBottom() : int; // Linest o discard at bottom
    public function getConcatEvery() : int; // Concatenate every X lines
    public function getConcatIndex() : int; // Concatenation index starting point
    public function getFieldsToImplode(): array; // Get the headers values for output


    /**
     * Function that implements the content parser
     *
     * @param array $content Content to be parsed
     * @return type
     * @throws conditon
     **/
    public function parser() : array;
}