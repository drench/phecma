<?php

// https://developer.mozilla.org/en/Core_JavaScript_1.5_Reference/Global_Objects/Math

class PHECMA_Math {

    static public $E       = M_E;
    static public $LN2     = M_LN2;
    static public $LN10    = M_LN10;
    static public $LOG2E   = M_LOG2E;
    static public $PI      = M_PI;
    static public $SQRT1_2 = M_SQRT1_2;
    static public $SQRT2   = M_SQRT2;

    static function abs    ($n)    { return abs($n); }
    static function acos   ($n)    { return acos($n); }
    static function asin   ($n)    { return asin($n); }
    static function atan   ($n)    { return atan($n); }
    static function atan2  ($n)    { return atan2($n); }
    static function ceil   ($n)    { return ceil($n); }
    static function cos    ($n)    { return cos($n); }
    static function exp    ($n)    { return exp($n); }
    static function floor  ($n)    { return floor($n); }
    static function log    ($n)    { return log($n); }
    static function max    ()      { return max(func_get_args()); }
    static function min    ()      { return min(func_get_args()); }
    static function pow    ($n,$p) { return pow($n, $p); }
    static function random ()      { return lcg_value(); }
    static function round  ($n)    { return round($n); }
    static function sin    ($n)    { return sin($n); }
    static function sqrt   ($n)    { return sqrt($n); }
    static function tan    ($n)    { return tan($n); }
}

$Math = new PHECMA_Math ();

?>
