<?php
include ("../database/database.php");
$conn = db_connect();


// kalem yang ini asal pake, belom diapa-apain wkwk

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $userID = $_SESSION['userID']; // Assuming userID is stored in session after login
//     $accountNumber = mt_rand(100000, 999999); // Generate a random account number
//     $balance = 0; // Initial balance
    
//     $sql = "INSERT INTO accounts (userID, accountNumber, balance) VALUES ('$userID', '$accountNumber', '$balance')";
    
//     if ($conn->query($sql) === TRUE) {
//         echo "Account created successfully!";
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }