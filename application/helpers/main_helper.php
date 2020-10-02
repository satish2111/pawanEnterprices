<?php

function date_compare_sort($a, $b) {
    $t1 = strtotime($a->Billdate);
    $t2 = strtotime($b->Billdate);
    return $t1 - $t2;
}

function name_compare_sort($a, $b) {
    return  strcmp($a->Name, $b->Name);
    
    
}

?>