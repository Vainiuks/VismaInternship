<?php

$dbHost     = "localhost"; 
$dbUsername = "root"; 
$dbPassword = ""; 
$dbName     = "vaccination"; 
 
// Create database connection 
$connection = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
