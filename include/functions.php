<?php


// Function for basic field validation (present and neither empty nor only white space
//Retuns TRUE if null or empty or not set
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

function connect_db(){
	//Setup Connection with MySQL database
	$servername = "localhost";
	$username= "bop_user";
	$password="G0n3W357";
	$dbname="compliance";
	$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	return $connection;
}
?>