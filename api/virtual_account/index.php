<?php
/**
 * 
 * API Virtual Account
 * Digunakan untuk melakukan pembayaran ke account tertentu
 * secara online
 * 
 */

include_once($_SERVER['DOCUMENT_ROOT'] . "/controller/virtual_account.php");

// make connection to db
$conn = dbConnect();

switch ($_SERVER["REQUEST_METHOD"]) {
    /**
     * 
     * make virtual account
     * request  : account_id, amount, information
     * response : virtual_account_number, amount, creation_datetime, expired_date
     * 
     */
    case "POST":
        $jsonData = file_get_contents('php://input');
    
        // decode JSON data
        $jsonDataDecode = json_decode($jsonData, true);

        // get data from json data decode
        $request["receiver_account_number"] = $jsonDataDecode["receiver_account_number"];
        $request["amount"] = $jsonDataDecode["amount"];
        $request["information"] = $jsonDataDecode["information"];

        // make virtual akun
        $dataTemp = createVirtualAccount($request);

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
     * response : receiver_account_id, amount, information, creation_datetime, expired_date, transaction_conditon
     * 
     */
    case "GET":
        $request["virtual_account_number"] = $_GET["virtual_account_number"];
        if (empty($request["virtual_account_number"]) == false) {
            // get data
            $data = getVirtualAccountData($request);
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
     * edit virtual account data: only use this for cancel virtual account
     * request  : account_number
     * response : 
     * 
     */
    case "PUT":
        $jsonData = file_get_contents('php://input');
    
        // decode JSON data
        $jsonDataDecode = json_decode($jsonData, true);
        $request = $jsonDataDecode;
        $virtualAccount = $_GET;

        $data = editVirtualAccount($request, $virtualAccount);
        break;
}

// close connection to db
mysqli_close($conn);

header('Content-Type: application/json');   // set type ke json
echo json_encode($data);                    // encode to json