<?php

function generateCode($str = '', $id = 0) {
    $codeID = $id ? strval($id) : "0";
    $codeID = strlen($codeID) < 5 ? str_repeat("0", 5 - strlen($codeID)).$codeID : $codeID;
    return $str."-".$codeID;
}