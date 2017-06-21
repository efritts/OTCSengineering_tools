<?php
// Function for basic field validation (present and neither empty nor only white space
//Retuns TRUE if null or empty or not set
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}
?>