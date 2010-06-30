<?php

// FIXME: use http://us3.php.net/manual/en/class.splstring.php ?

class PHECMA_String extends PHECMA_Object {

    public function __get ($prop) {
        switch($prop) {
            case 'length':
                return strlen($this->value);
            default:
                throw new Exception("unknown property $prop");
        }
    }

    public function charAt ($n) {
        return substr($this->value, $n, 1);
    }

    public function charCodeAt ($n) {
        return ord($this->charAt($n));
    }

    public function concat () { # can have unlimited args
        return $this->value . join(func_get_args(), '');
    }

    public function indexOf ($s) {
        throw new Exception('unimplemented');
    }

    public function lastIndexOf ($s) {
        throw new Exception('unimplemented');
    }

    public function localeCompare ($s) {
        throw new Exception('unimplemented');
    }

    public function match ($s) {
        throw new Exception('unimplemented');
    }

    public function quote ($s) {
        throw new Exception('unimplemented');
    }

    public function replace ($s) {
        throw new Exception('unimplemented');
    }

    public function search ($s) {
        throw new Exception('unimplemented');
    }

    public function slice ($s) {
        throw new Exception('unimplemented');
    }

    public function split ($separator = false, $limit = 1) {
        if ($separator == false) {
            return array($this->value);
        }
        else {
            return explode($separator, $this->value, $limit);
        }
    }

    public function substr ($s) {
        throw new Exception('unimplemented');
    }

    public function substring ($s) {
        throw new Exception('unimplemented');
    }

    public function toLocaleLowerCase () {
        return strtolower($this->value); // FIXME
    }

    public function toLocaleUpperCase () {
        return strtoupper($this->value); // FIXME
    }

    public function toLowerCase () {
        return strtolower($this->value);
    }

    public function toUpperCase () {
        return strtoupper($this->value);
    }

    public function trim () {
        return trim($this->value);
    }
}

?>
