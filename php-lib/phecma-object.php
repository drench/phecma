<?php

class PHECMA_Object {

    public $value = NULL;
    private static $proto = array();

    public function __construct ($o) {
        $this->value = $o;
    }

    public function __toString () {
        return ''.$this->value;
    }

    public function __call ($fn, $arg) {
        $f = self::$proto[$fn];
        $self = $this;
        return $f($arg); # FIXME what about '$this'?
    }

    public function __get ($prop) {
        return self::$proto[$prop];
    }

    public static function prototype ($prop, $val) {
        self::$proto[$prop] = $val;
    }

    public function type () {
        return get_class($this);
    }
}

?>
