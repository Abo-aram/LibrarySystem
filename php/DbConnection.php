<?php
$serverName = 'localhost';
$username = 'root';
$password = '';
$database = 'library_system';

$conn = new mysqli($serverName, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
