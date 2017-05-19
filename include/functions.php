<?php


// Function for basic field validation (present and neither empty nor only white space
//Retuns TRUE if null or empty or not set
function IsNullOrEmptyString($question){
    return (!isset($question) || trim($question)==='');
}

function connect_db(){
	//Setup Connection with MySQL database
	$servername = "localhost";
	$username= "user";
	$password="password";
	$dbname="database";
	$connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	return $connection;
}
?>