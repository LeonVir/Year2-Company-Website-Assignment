<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "company_db";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
