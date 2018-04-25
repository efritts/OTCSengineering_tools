<?php

function connect_db(){
    //Setup Connection with MySQL database
    
    $servername = "us-cdbr-iron-east-05.cleardb.net";
    $username= "b2d3fec699a396";
    $password="4fda2c9a";
    $dbname="heroku_fd05977f8785f28";
    $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    return $connection;
}
//mysql://b2d3fec699a396:4fda2c9a@us-cdbr-iron-east-05.cleardb.net/heroku_fd05977f8785f28?reconnect=true
?>
