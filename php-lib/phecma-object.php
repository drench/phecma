<?php

class PHECMA_Object {

    public $value = NULL;

    public function __construct ($o) {
        $this->value = $o;
    }

    public function __toString () {
        return ''.$this->value;
    }

    public function type () {
        return get_class($this);
    }
}

?>
