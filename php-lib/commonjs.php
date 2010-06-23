<?php

// http://commonjs.org/

class CommonJS {
    public static function _require ($module) {

        $r = NULL;

        switch ($module) {
            case 'file':
                require_once('commonjs-file.php');
                $r = new CommonJS_file ();
                break;

            case 'xhr':
                require_once('commonjs-xhr.php');
                $r = new CommonJS_xhr ();
                break;

            default:
                throw new Exception("Unsupported module '$module'");
        }

        return $r;
    }
}

?>
