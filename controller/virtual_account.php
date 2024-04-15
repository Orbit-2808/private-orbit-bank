<?php
include_once(__DIR__ . "/database/database.php");
include_once(__DIR__ . "/controller/transaction.php");

function generateVirtualAccountNumber($receiverAccountNumber) {
    // concat date and receiver account number to get virtual account number
    $virtualAccountNumber = date("YmdHis") . $receiverAccountNumber;

    // erase head of year: like 19, 20.
    $virtualAccountNumber = substr($virtualAccountNumber, 2);

    return $virtualAccountNumber;
}

function getVirtualAccountData($request) {
    $conn = dbConnect();
    $sql = "SELECT virtual_account_id, receiver_account_id, amount, information, creation_datetime, expired_date, transaction_conditon
            FROM virtual_accounts
            WHERE virtual_account_number = '{$request["virtual_account_number"]}'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $data;
}

function createVirtualAccount($request) {
    $conn = dbConnect();

    // get data needed
    $account_id = _getAccountId($request["receiver_account_number"], $conn);
    $virtualAccountNumber = generateVirtualAccountNumber($request["receiver_account_number"]);
    
    // insert data
    $sql = "INSERT INTO `virtual_accounts`
            (`receiver_account_id`, `virtual_account_number`, `amount`, `information`, `creation_datetime`, `expired_date`, `transaction_conditon`)
            VALUES
            ($account_id, '$virtualAccountNumber', {$request["amount"]}, '{$request["information"]}', current_timestamp(), ADDDATE(current_timestamp(), INTERVAL 1 DAY), 'waiting for transaction')";
    $result = mysqli_query($conn, $sql);
    
    $virtualAccountId = mysqli_insert_id($conn);

    mysqli_close($conn);

    // get data
    $data = getVirtualAccountData($virtualAccountNumber);
    $data["virtual_account_number"] = $virtualAccountNumber;

    // don't return all data
    return $data;
}

function editVirtualAccount($request, $virtualAccount) {
    $conn = dbConnect();

    $virtualAccountData = getVirtualAccountData($virtualAccount);
    
    if(empty($request["receiver_account_number"]) == false)
        $request["receiver_account_id"] = _getAccountId($request["receiver_account_number"], $conn);


    $updates = [
        'receiver_account_id' => $request["receiver_account_id"],
        'virtual_account_number' => $request["virtual_account_number"],
        'amount' => $request["amount"],
        'information' => $request["information"],
        'expired_date' => $request["expired_date"]
    ];
    
    // Loop through the updates array and construct the SET clause
    foreach ($updates as $column => $value) {
        if ($value !== null) {
            $setClauses[] = "`$column` = '$value'";
        }
    }
    
    // Join the SET clause elements with commas
    $setClause = implode(", ", $setClauses);

    $sql = "UPDATE `virtual_accounts`
        SET
        $setClause
        WHERE
        virtual_account_id = {$virtualAccountData["virtual_account_id"]}";

    // return $sql;
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);

    // get data
    $virtualAccountData = getVirtualAccountData($virtualAccount);

    return $virtualAccountData;
}