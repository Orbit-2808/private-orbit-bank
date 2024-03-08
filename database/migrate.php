<?php
require("database.php");
$conn = db_connect();
$sqlFile = 'payment_db.sql';

// Read the SQL file
$sql = file_get_contents($sqlFile);

// Execute the SQL queries
if (mysqli_multi_query($conn, $sql)) {
    echo "SQL file imported successfully\n";
} else {
    echo "Error importing SQL file: " . mysqli_error($conn) . "\n";
}

// Close connection
mysqli_close($conn);