<?php
/**
 * 
 * API Virtual Account
 * Digunakan untuk melakukan pembayaran ke account tertentu
 * secara online
 * 
 */

include_once($_SERVER['DOCUMENT_ROOT'] . "/module/virtual_account.php");

// make connection to db
$conn = db_connect();

switch ($_SERVER["REQUEST_METHOD"]) {
    /**
     * 
     * make virtual account
     * request  : account_id, amount
     * response :
     */
    case "POST":
        $json_data = file_get_contents('php://input');
    
        // decode JSON data
        $json_data_decode = json_decode($json_data, true);

        // get data from json data decode
        $account_id = $json_data_decode["account_id"];
        $amount = $json_data_decode["amount"];

        $data = create_virtual_account($conn, $account_id, $amount);    // make virtual akun
        break;


    /**
     * 
     * get virtual account data
     * request  : virtual_account_number
     * response : 
     * 
     */
    case "GET":
        $virtual_account_number = $_GET["virtual_account_number"];
        if (empty($virtual_account_number) == false) {
            $data = get_virtual_account_data($conn, $virtual_account_number);       // get data
        } else {
            // send error data
            $data = array(
                "error" => array(
                    "code" => 400,
                    "message" => "Bad Request",
                )
            );
        }
        break;

    /**
     * 
     * edit virtual account data
     * request  : account_number
     * response : 
     * 
     */
    case "PUT":
        break;
}

// close connection to db
mysqli_close($conn);

header('Content-Type: application/json');   // set type ke json
echo json_encode($data);                    // encode to json