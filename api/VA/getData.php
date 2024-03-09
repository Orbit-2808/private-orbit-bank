<?php
include_once("../../database/virtual_account.php");

header('Content-Type: application/json'); // set type ke json

if($_SERVER["REQUEST_METHOD"] == "GET") { // untuk mengambil data virtual akun, gunakan method get
    // tangkap data form
    $virtual_account_number = $_GET["virtual_account_number"];

    if(empty($virtual_account_number) == false) {
        // buat koneksi
        $conn = db_connect();
    
        $data = get_virtual_account_data($conn, $virtual_account_number);    // buat virtual akun
        echo json_encode($data);                                        // tulis data dalam bentuk encode
    
        // tutup koneksi
        $conn->close();
    } else {
        // kirim data error
        $error = array(
            "error" => array(
                "code" => 400,
                "message" => "Bad Request",
            )
        );
        
        echo json_encode($error);
    }

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