<?php
$mysqli = new mysqli('localhost', 'root', '');
if ($mysqli->connect_error) {
    echo 'Connection failed: ' . $mysqli->connect_error;
    exit(1);
}

$sql = 'DROP DATABASE IF EXISTS wedding_batak_test;';
if ($mysqli->query($sql) === TRUE) {
    echo "Database 'wedding_batak_test' dropped successfully\n";
} else {
    echo 'Error: ' . $mysqli->error;
    exit(1);
}

$sql = 'CREATE DATABASE IF NOT EXISTS wedding_batak_test CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;';
if ($mysqli->query($sql) === TRUE) {
    echo "Database 'wedding_batak_test' created successfully\n";
} else {
    echo 'Error: ' . $mysqli->error;
    exit(1);
}

$mysqli->close();
?>
