<?php
function setEnvironmentVariables() {
    $_ENV["DATABASE_HOST"] = "localhost:3307";
    $_ENV["DATABASE_USER"] = "root";
    $_ENV["DATABASE_PASSWORD"] = "";
    $_ENV["DATABASE_NAME"] = "orbit_bank_db";
}

function dbConnect() {
    // set environment variables
    setEnvironmentVariables();
    
    // Create connection
    $conn = mysqli_connect($_ENV["DATABASE_HOST"], $_ENV["DATABASE_USER"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);

    // Check connection
    if (!$conn) die("Connection failed: " . mysqli_connect_error());

    return $conn;
}

