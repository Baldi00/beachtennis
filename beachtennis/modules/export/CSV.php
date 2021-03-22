<?php

class CSV {

    private $file;

    function __construct($fileName) {
        $this->file = fopen($fileName.".csv", "w");
    }

    function __destruct() {
        // TODO: closing the file in the destructor throw an error. Why?
        // fclose($this->file);
    }

    function addElement($element) {
        fwrite($this->file, $element.";");
    }

    function newLine() {
        fwrite($this->file, "\r\n");
    }

    function close() {
        fclose($this->file);
    }

}