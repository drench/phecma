<?php

// http://commonjs.org/

class CommonJS {
    public static function _require ($module) {

        $r = NULL;

        switch ($module) {
            case 'fs':
                require_once('commonjs-fs.php');
                $r = new CommonJS_fs ();
                break;

            case 'jsgi':
                require_once('commonjs-jsgi.php');
                $r = new CommonJS_jsgi ();
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
