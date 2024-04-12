<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/module/transaction.php");

function generateVirtualAccountNumber($receiverAccountNumber) {
    // concat date and receiver account number to get virtual account number
    $virtualAccountNumber = date("YmdHis") . $receiverAccountNumber;

    // erase head of year: like 19, 20.
    $virtualAccountNumber = substr($virtualAccountNumber, 2);

    return $virtualAccountNumber;
}

function getVirtualAccountData($virtualAccountNumber) {
    $conn = db_connect();
    $sql = "SELECT receiver_account_id, amount, information, creation_datetime, expired_date, transaction_conditon
            FROM virtual_accounts
            WHERE virtual_account_number = '$virtualAccountNumber'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    mysqli_close($conn);
    return $data;
}

function createVirtualAccount($receiverAccountNumber, $amount, $information) {
    $conn = db_connect();

    // get data needed
    $account_id = _getAccountId($receiverAccountNumber, $conn);
    $virtualAccountNumber = generateVirtualAccountNumber($receiverAccountNumber);

    // insert data
    $sql = "INSERT INTO `virtual_accounts`
            (`receiver_account_id`, `virtual_account_number`, `amount`, `information`, `creation_datetime`, `expired_date`, `transaction_conditon`)
            VALUES
            ($account_id, '$virtualAccountNumber', $amount, '$information', current_timestamp(), ADDDATE(current_timestamp(), INTERVAL 1 DAY), 'waiting for transaction')";

    $result = mysqli_query($conn, $sql);

    $virtualAccountId = mysqli_insert_id($conn);

    mysqli_close($conn);

    // get data
    $data = getVirtualAccountData($virtualAccountNumber);
    $data["virtual_account_number"] = $virtualAccountNumber;

    // don't return all data
    return $data;
}