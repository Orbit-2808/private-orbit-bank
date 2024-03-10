<?php
include_once("database.php");

$conn = db_connect();

// Path to your SQL file
$sql_file = "payment_db.sql";

// Read the SQL file
$sql = file_get_contents($sql_file);

// Execute each query separated by a semicolon
if ($conn->multi_query($sql)) {
    echo "SQL file executed successfully.\n";
} else {
    echo "Error executing SQL file: " . $conn->error . "\n";
}

// Close connection
$conn->close();