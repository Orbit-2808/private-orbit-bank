<?php
include_once("../../database/virtual_account.php");

header('Content-Type: application/json'); // set type ke json

if($_SERVER["REQUEST_METHOD"] == "POST") { // untuk membuat virtual akun, gunakan method post
    // tangkap data form
    $account_id = $_POST["account_id"];
    $amount = $_POST["amount"];

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