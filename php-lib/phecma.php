<?php

function PHECMA_adder () {
    $args = func_get_args();
    $sum = array_shift($args);

    foreach ($args as $arg) {
        if (is_numeric($sum) && is_numeric($arg)) {
            $sum += $arg;
        }
        else {
            $sum .= $arg;
        }
    }

    return $sum;
}

require_once('phecma-object.php');
require_once('phecma-math.php');
require_once('phecma-regexp.php');
require_once('phecma-string.php');

?>
