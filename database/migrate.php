<?php
include_once("config.php");

function setDB($conn, $database_name) {
    $sql = "DROP DATABASE IF EXISTS {$database_name}; CREATE DATABASE {$database_name}";

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
    $sql_file = $database_name . ".sql";
    
    // read the SQL file
    $sql = file_get_contents($sql_file);
    
    // select the database
    if (!mysqli_select_db($conn, $database_name)) {
        die("Failed to select database: " . mysqli_error($conn));
    }
    
    // execute each query separated by a semicolon
    if (mysqli_multi_query($conn, $sql)) {
        // loop through all results to clear them from the buffer
        do {
            if ($result = mysqli_store_result($conn)) {
                mysqli_free_result($result);
            }
        } while (mysqli_next_result($conn));
    
        echo "SQL file executed successfully.\n";
    } else {
        echo "Error executing SQL file: " . mysqli_error($conn) . "\n";
    }
}

/**
 * Main
 */
// set environment variables
setEnvironmentVariables("");

// connect to mysql without use database
$conn = mysqli_connect($_ENV["DATABASE_HOST"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASSWORD"]);

setDB($conn, "orbit_bank_db");
setDB($conn, "indonesian_regional_db");

// close connection
mysqli_close($conn);