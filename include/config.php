<?php

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
