<?php

class PHECMA_RegExp {

    private $pattern   = NULL;
    private $global    = false;

    public function __construct ($pattern, $modifiers = '') {
        $m = preg_replace('/g/', '', $modifiers);
        if ($m) {
            $modifiers = $m;
            $this->global = true;
        }

        # don't want a full preg_quote() here, just slashes:
        $pattern = preg_replace('/\//', '\\/', $pattern);

        $this->pattern = "/$pattern/$modifiers";
    }

    public function compile () {
        // no-op
        return true;
    }

# FIXME
# the arrays returned aren't the same format as js regexes
    public function exec ($subject) {

        if ($this->global) {
            $r = preg_match_all($this->pattern, $subject, $matches);
        }
        else {
            $r = preg_match($this->pattern, $subject, $matches);
        }

        if ($r) {
            $mr = new PHECMA_Array ();
            $mr->value = $matches[0]; // FIXME why?
            return $mr;
        }
        else {
            return false;
        }
    }

    public function test ($subject) {
        $m = NULL;
        $matches = array();
        if ($this->global) {
            $m = preg_match_all($this->pattern, $subject, $matches);
        }
        else {
            $m = preg_match($this->pattern, $subject, $matches);
        }
        return ($m == 0) ? false : true;
    }

}

?>
