<?php

function db_connect() {
    $servername = "localhost:3307";
    $username = "root";
    $password = "";
    $dbname = "payment_db";
    
    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}
?>
