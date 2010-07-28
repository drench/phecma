<?php

// https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Array

// FIXME this should be an ArrayObject(), somehow

class PHECMA_Array extends PHECMA_Object implements ArrayAccess {

    public function __get ($prop) {
        switch($prop) {
            case 'length':
                return count($this->value);
            default:
                return parent::__get($prop);
        }
    }

    public function __construct () {

        $n = func_num_args();

        if ($n > 0) {
            $args = func_get_args();
            if (($n == 1) && (is_int($args[0])) && ($args[0] > 0)) {
                $this->value = array();
                array_pad($this->value, $args[0], NULL);
            }
            else {
                $this->value = $args;
            }
        }
        else {
            $this->value = array();
        }
    }

    public function offsetExists ($i) {
        return isset($this->value[$i]);
    }

    public function offsetGet ($i) {
        return $this->value[$i];
    }

    public function offsetSet ($i, $v) {
        $this->value[$i] = $v;
    }

    public function offsetUnset ($i) {
        unset($this->value[$i]);
    }

    public function pop () {
        return array_pop($this->value);
    }

    public function push () {
        $args = func_get_args();
        $n = func_num_args();
        while ($n > 0) {
            array_push($this->value, array_shift($args));
            --$n;
        }
    }

// https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Array/reverse
    public function reverse () {
        $this->value = array_reverse($this->value);
        return $this;
    }

    public function shift () {
        return array_shift($this->value);
    }

    public function sort ($fn = NULL) {
        $r = new PHECMA_Array ();
        $r->value = $this->value;

        if ($fn) {
            usort($r->value, $fn);
        }
        else {
            sort($r->value, SORT_STRING);
        }

        return $r;
    }

    public function splice () {
        $args = func_get_args();
        $index = array_shift($args);
        $howMany = (count($args) == 0) ? 0 : array_shift($args);

        $r = new PHECMA_Array ();
        $r->value = array_splice($this->value, $index, $args);
        return $r;
    }

    public function unshift () {
        $args = func_get_args();
        $n = func_num_args();
        while ($n > 0) {
            array_unshift($this->value, array_shift($args));
            --$n;
        }
    }

    public function concat () {
        $args = func_get_args();
        $n = func_num_args();
        $na = new PHECMA_Array ();

        while ($n > 0) {
            $v = array_shift($args);
            if (is_array($v)) { // FIXME PHECMA_Arrays too?
                while (count($v) > 0) {
                    array_push($na->value, array_shift($v));
                }
            }
            else {
                array_push($na->value, $v);
            }
            --$n;
        }

        return $na;
    }

    public function join ($sep = ',') {
        return implode($sep, $this->value);
    }

    public function slice ($begin, $end = false) {
        $r = new PHECMA_Array ();
        if ($end) {
            if ($end > 0) $end = count($this->value) - $end;
            $r->value = array_slice($this->value, $begin, $end);
        }
        else {
            $r->value = array_slice($this->value, $begin);
        }
        return $r;
    }

    public function toString () {
        // "wrong" if any elements have commas, but this is how Rhino does it
        return $this->join(',');
    }

    public function indexOf ($s, $from = 0) {
//    would it be better to:
//      slice the array based on $from, then call in_array.
//      if in_array is true, then figure out where it is (?)
        $len = count($this->value);

        if ($from >= $len) return -1;
        if ($from < 0) {
            $from = $len + $from;
            if ($from < 0) return -1;
        }

        do {
            if ($this->value[$from] == $s) return $from;
            ++$from;
        } while ($len > $from);

        return -1;
    }

// https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Array/lastIndexOf
    public function lastIndexOf () {
        $args = func_get_args();
        $s = array_shift($args);
        $from = array_shift($args);

        $len = count($this->value);

        if (($from == NULL) || ($from >= $len)) {
            $from = $len - 1;
        }

        if ($from < 0) {
            $from = $len + $from;
            if ($from < 0) return -1;
        }

        do {
            if ($this->value[$from] == $s) return $from;
            --$from;
        } while ($from > -1);

        return -1;
    }

    public function filter ($callback, $mythis = NULL) {
// PHP's array_filter() is close, but not an exact fit so I'm not using it
// FIXME : doesn't support "$mythis"
        $len = $this->length;
        $n = 0;
        $r = new PHECMA_Array ();

        while ($n < $len) {
            $v = $this->value[$n];
            if (! is_null($v)) {
                $x = $callback($v, $n, $this);
                if ($x == true) array_push($r->value, $v);
            }
            ++$n;
        }
        return $r;
    }

// FIXME: "forEach" confuses PHP's parser
    public function _forEach ($callback, $mythis = NULL) {
// FIXME : doesn't support "$mythis"
        $len = $this->length;
        $n = 0;

        while ($n < $len) {
            $callback($v, $n, $this);
            ++$n;
        }
    }

    public function every ($callback, $mythis = NULL) {
// FIXME : doesn't support "$mythis"
        $len = $this->length;
        $n = 0;

        while ($n < $len) {
            $r = $callback($this->value[$n], $n, $this);
            if (! $r) return false;
            ++$n;
        }

        return true;
    }

    public function map ($callback, $mythis = NULL) {
// FIXME : doesn't support "$mythis"
        $len = $this->length;
        $n = 0;
        $r = new PHECMA_Array ();

        while ($n < $len) {
            $v = $this->value[$n];
            if (! is_null($v)) {
                array_push($r->value, $callback($v, $n, $this));
            }
            ++$n;
        }

        return $r;
    }

    public function some ($callback, $mythis = NULL) {
// FIXME : doesn't support "$mythis"
        $len = $this->length;
        $n = 0;

        while ($n < $len) {
            $r = $callback($this->value[$n], $n, $this);
            if ($r) return true;
            ++$n;
        }

        return false;
    }

    public function reduce ($callback, $v = NULL) {
        $len = $this->length;
        $n = 0;

        while (is_null($v) && ($n < $len)) {
            $v = $this->value[$n];
            ++$n;
        }

        while ($n < $len) {
            $v = $callback($v, $this->value[$n], $n, $this);
            ++$n;
        }

        return $v;
    }

    public function reduceRight ($callback, $v = NULL) {
        $len = $this->length;
        $n = $len - 1;

        while (is_null($v) && ($n > -1)) {
            $v = $this->value[$n];
            --$n;
        }

        while ($n > -1) {
            $v = $callback($v, $this->value[$n], $n, $this);
            --$n;
        }

        return $v;
    }
}

?>
