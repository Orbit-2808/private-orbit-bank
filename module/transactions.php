<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
$conn = db_connect();

enum TransactionType {
    case debit;
    case credit;
}

function _getProfile($accountId, $conn) {
    $query = "SELECT name, address, email
                FROM accounts
                INNER JOIN users ON accounts.user_id = users.user_id
                WHERE accounts.account_id = $accountId";
    
    $result = mysqli_query($conn, $query);

    if($result) {
        while($temp = mysqli_fetch_assoc($result)) {
            $row[] = $temp;
        }
    }

    mysqli_close($conn);
    return $row;
}

function _getBalanceRecords($accountId, $conn) {
    $query = "SELECT record_date, debit, credit, balance
                FROM balances
                INNER JOIN accounts ON balances.balance_id = accounts.account_id
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
    global $conn;

    $query = "SELECT balance
                FROM balances
                WHERE account_id = '$accountId'
                ORDER BY balances.record_date DESC
                LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $temp = mysqli_fetch_assoc($result);
        $accountId = $temp["account_id"];
    } else {
        $accountId = -1;
    }

    return $accountId;
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

function saveMoney($accountNumber, $amount) {
    global $conn;
    $accountId = _getAccountId($accountNumber, $conn);
    $balanceId = _recordNewBalance($accountId, $amount, TransactionType::credit, $conn);
    $query = "INSERT INTO savings (balance_id, type)
                VALUES($balanceId, 'save')";
    $result = mysqli_query($conn, $query);
    $savingsId = mysqli_insert_id($conn);
    mysqli_close($conn);
    return $savingsId;
}

function withdrawMoney() {}

function transferBeetweenAccounts($senderAccountNumber, $receiveAccountNumber, $mount) {
    global $conn;
    $senderAccountId = _getAccountId($senderAccountNumber, $conn);
    $receiveAccountId = _getAccountId($receiveAccountNumber, $conn);

    if($senderAccountId != -1 && $receiveAccountId != -1) {
        $senderBalanceId = _recordNewBalance($senderAccountId, $mount, TransactionType::debit, $conn);
        $receiveBalanceId = _recordNewBalance($receiveAccountId, $mount, TransactionType::credit, $conn);
        $query = "INSERT INTO transfers (sender_balance_id, receive_balance_id)
                    VALUES($senderBalanceId, $receiveBalanceId)";
        $result = mysqli_query($conn, $query);
        $transferId = mysqli_insert_id($conn);
    } else {
        $transferId = -1;
    }

    mysqli_close($conn);
    return $transferId;
}