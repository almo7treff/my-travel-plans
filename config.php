<?php
$host = 'localhost';
$dbname = 'opalpixe_demo';
$user = 'opalpixe_demo1';
$password = 'aHmEd@987654321';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>