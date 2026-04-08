<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$port = 3307;

$conn = new mysqli($servername, $username, $password, "", $port);

$dbname = "company_db";
$sql = "CREATE DATABASE $dbname";

$conn->query($sql);
$conn->close();
