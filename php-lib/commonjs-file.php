<?php

class CommonJS_file {

    public $file; // FIXME name subject to change

    public function __construct () {
        $this->CommonJS_file_file = new CommonJS_file_file ();
    }

}

class CommonJS_file_file {

    public function open ($path, $flags, $permissions, $encoding) {
        throw new Exception ('stub!');
    }

}

?>
