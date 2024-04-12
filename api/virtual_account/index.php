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
        $jsonData = file_get_contents('php://input');
    
        // decode JSON data
        $jsonDataDecode = json_decode($jsonData, true);

        // get data from json data decode
        $receiveAccountId = $jsonDataDecode["receive_account_id"];
        $amount = $jsonDataDecode["amount"];
        $information = $jsonDataDecode["information"];
        
        // make virtual akun
        $dataTemp = createVirtualAccount($receiveAccountId, $amount, $information);

        // dont expose all data
        $data = [
            "virtual_account_number" => $dataTemp["virtual_account_number"],
            "amount" => (int) $dataTemp["amount"],
            "creation_datetime" => $dataTemp["creation_datetime"],
            "expired_date" => $dataTemp["creation_datetime"],
        ];
        break;


    /**
     * 
     * get virtual account data
     * request  : virtual_account_number
     * response : 
     * 
     */
    case "GET":
        $virtualAccountNumber = $_GET["virtual_account_number"];
        if (empty($virtualAccountNumber) == false) {
            // get data
            $data = getVirtualAccountData($virtualAccountNumber);
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