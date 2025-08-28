<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Testing DB connection...<br>";

$host = "sql201.infinityfree.com";
$user = "if0_39807668";
$password = "ERx0yDmKq8V";
$dbname = "if0_39807668_cybot_cafe";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully!";
}
?>
