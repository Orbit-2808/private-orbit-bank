<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/controller/transaction.php");

// run if allowed http method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    die();
} else {
    // get submit value
    $submitValue = $_POST["submit"];
}

switch ($submitValue) {
    case "Save":
        saveMoney($_POST["account_number"], $_POST["amount"]);
        break;

    case "Withdraw":
        saveMoney($_POST["account_number"], $_POST["amount"]);
        break;

    case "Transfer":
        if($_POST["sender_account_number"] != $_POST["receiver_account_number"])
            transferBeetweenAccounts($_POST["sender_account_number"], $_POST["receiver_account_number"], $_POST["amount"]);
        break;

    default:
        break;
}

header("Location: ../");
die();