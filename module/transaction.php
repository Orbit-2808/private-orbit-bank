<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");

enum TransactionType {
    case debit;
    case credit;
}

function getProfile($username) {
    $conn = db_connect();
    $query = "SELECT name, address, email, account_number
                FROM accounts
                INNER JOIN users ON accounts.user_id = users.user_id
                WHERE users.username = '$username'";
    
    $result = mysqli_query($conn, $query);

    if($result) {
        $row = mysqli_fetch_assoc($result);
    }

    mysqli_close($conn);
    return $row;
}

function _getAccountId($accountNumber, $conn) {
    $query = "SELECT account_id FROM accounts WHERE account_number = '$accountNumber'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $temp = mysqli_fetch_assoc($result);
        $accountId = $temp["account_id"];
    } else {
        $accountId = -1;
    }

    return $accountId;
}

function _getCurrentBalance($accountId, $conn) {
    $conn = db_connect();

    $query = "SELECT balance
                FROM balances
                WHERE account_id = $accountId
                ORDER BY balances.record_date DESC
                LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $temp = mysqli_fetch_assoc($result);
        $balance = $temp["balance"];
    } else {
        $balance = 0;
    }

    return $balance;
}

function _recordNewBalance($accountId, $amount, $transactionType, $conn) {
    $previousBalance = _getCurrentBalance($accountId, $conn);

    switch ($transactionType) {
        case TransactionType::debit:
            $currentBalance = $previousBalance - $amount;
            $query = "INSERT INTO balances (balances.account_id, debit, balance)
                        VALUES($accountId, $amount, $currentBalance)";
            break;

        case TransactionType::credit:
                $currentBalance = $previousBalance + $amount;
                $query = "INSERT INTO balances (balances.account_id, credit, balance)
                        VALUES($accountId, $amount, $currentBalance)";
            break;

        default:
            echo "Wrong Type";
            break;
    }
        
    $result = mysqli_query($conn, $query);
    return mysqli_insert_id($conn);
}

function getBalanceRecords($accountNumber) {
    $conn = db_connect();
    $accountId = _getAccountId($accountNumber, $conn);
    
    $query = "SELECT record_date, debit, credit, balance
                FROM balances
                WHERE balances.account_id = $accountId";
    
    $result = mysqli_query($conn, $query);

    if($result) {
        while($temp = mysqli_fetch_assoc($result)) {
            $row[] = $temp;
        }
    }

    mysqli_close($conn);
    return $row;
}

function saveMoney($accountNumber, $amount) {
    $conn = db_connect();
    $accountId = _getAccountId($accountNumber, $conn);
    $balanceId = _recordNewBalance($accountId, $amount, TransactionType::credit, $conn);
    $query = "INSERT INTO savings (balance_id, type)
                VALUES($balanceId, 'save')";
    $result = mysqli_query($conn, $query);
    $savingsId = mysqli_insert_id($conn);
    mysqli_close($conn);
    return $savingsId;
}

function withdrawMoney($accountNumber, $amount) {}

function transferBeetweenAccounts($senderAccountNumber, $receiverAccountNumber, $mount) {
    $conn = db_connect();
    $senderAccountId = _getAccountId($senderAccountNumber, $conn);
    $receiverAccountId = _getAccountId($receiverAccountNumber, $conn);

    if($senderAccountId != -1 && $receiverAccountId != -1) {
        $senderBalanceId = _recordNewBalance($senderAccountId, $mount, TransactionType::debit, $conn);
        $receiverBalanceId = _recordNewBalance($receiverAccountId, $mount, TransactionType::credit, $conn);
        $query = "INSERT INTO transfers (sender_balance_id, receiver_balance_id)
                    VALUES($senderBalanceId, $receiverBalanceId)";
        $result = mysqli_query($conn, $query);
        $transferId = mysqli_insert_id($conn);
    } else {
        $transferId = -1;
    }

    mysqli_close($conn);
    return $transferId;
}