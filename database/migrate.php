<?php
include_once("database.php");

// set environment variables
setEnvironmentVariables();

// connect to mysql without use database
$conn = mysqli_connect($_ENV["DATABASE_HOST"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASSWORD"]);

$sql = "DROP DATABASE IF EXISTS {$_ENV["DATABASE_NAME"]}; CREATE DATABASE {$_ENV["DATABASE_NAME"]}";

// execute each query separated by a semicolon
if (mysqli_multi_query($conn, $sql)) {
    // loop through all results to clear them from the buffer
    do {
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));

    echo "Database is created successfully.\n";
} else {
    echo "Error creating database " . mysqli_error($conn) . "\n";
}

// path to your SQL file
$sql_file = "orbit_bank_db.sql";

// read the SQL file
$sql = file_get_contents($sql_file);

// select the database
if (!mysqli_select_db($conn, $_ENV["DATABASE_NAME"])) {
    die("Failed to select database: " . mysqli_error($conn));
}

// execute each query separated by a semicolon
if (mysqli_multi_query($conn, $sql)) {
    echo "SQL file executed successfully.\n";
} else {
    echo "Error executing SQL file: " . mysqli_error($conn) . "\n";
}

// close connection
mysqli_close($conn);