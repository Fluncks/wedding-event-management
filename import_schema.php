<?php
$db = new mysqli('localhost', 'root', '', 'wedding_batak_test');
if ($db->connect_error) {
    echo 'Connection failed: ' . $db->connect_error;
    exit(1);
}

$sql = file_get_contents(__DIR__ . '/database/wedding_batak.sql');

// Remove the USE and CREATE DATABASE statements, replace table names
$sql = preg_replace('/USE `?wedding_batak`?;/i', '', $sql);
$sql = preg_replace('/CREATE DATABASE .+?;/i', '', $sql);

// Split by semicolon and execute each statement
$statements = array_filter(array_map('trim', explode(';', $sql)));

foreach ($statements as $stmt) {
    if (!empty($stmt)) {
        if ($db->query($stmt) === false) {
            echo 'Error: ' . $db->error . PHP_EOL;
            echo 'Statement: ' . substr($stmt, 0, 100) . '...' . PHP_EOL;
        }
    }
}

echo "Schema imported successfully to wedding_batak_test database\n";
$db->close();
?>
