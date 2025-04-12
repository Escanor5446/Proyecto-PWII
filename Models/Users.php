<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capadb";

$conexion = mysqli_connect("localhost","root","","capadb");


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
}

return $conn;

?>