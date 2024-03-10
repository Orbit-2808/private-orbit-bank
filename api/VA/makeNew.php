<?php
include_once("../../database/virtual_account.php");

header('Content-Type: application/json'); // set type ke json

if($_SERVER["REQUEST_METHOD"] == "POST") { // untuk membuat virtual akun, gunakan method post
    $json_data = file_get_contents('php://input');
    
    // Decode JSON data
    $json_decode = json_decode($json_data, true);

    // tangkap data form
    $account_id = $json_decode["account_id"];
    $amount = $json_decode["amount"];

    // buat koneksi
    $conn = db_connect();

    $data = create_virtual_account($conn, $account_id, $amount);    // buat virtual akun
    echo json_encode($data);                                        // tulis data dalam bentuk encode

    // tutup koneksi
    $conn->close();
} else {
    // kirim data error
    $error = array(
        "error" => array(
            "code" => 405,
            "message" => "Not Allowed",
        )
    );
    
    echo json_encode($error);
}