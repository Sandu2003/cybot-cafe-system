<?php

$host = "sql201.infinityfree.com";
$user = "if0_39807668";
$password = "ERx0yDmKq8V";
$dbname = "if0_39807668_cybot_cafe";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

