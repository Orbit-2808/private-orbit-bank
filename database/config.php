<?php
function setEnvironmentVariables($databaseName) {
    $_ENV["DATABASE_HOST"] = "localhost:3307";
    $_ENV["DATABASE_USER"] = "root";
    $_ENV["DATABASE_PASSWORD"] = "";
    $_ENV["DATABASE_NAME"] = $databaseName;
}

function dbConnect($databaseName) {
    // set environment variables
    setEnvironmentVariables($databaseName);
    
    // Create connection
    $conn = mysqli_connect($_ENV["DATABASE_HOST"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);

    // Check connection
    if (!$conn) die("Connection failed: " . mysqli_connect_error());

    return $conn;
}

