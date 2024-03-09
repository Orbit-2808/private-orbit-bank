<?php
include_once("database.php");

function get_virtual_account_data(Mysqli $conn, int $virtual_account_number) {
    $sql = "SELECT * FROM `virtual_accounts`
        INNER JOIN `transactions` ON `virtual_accounts`.`transaction_id` = `transactions`.`transaction_id`
        WHERE `virtual_account_number` = $virtual_account_number
        LIMIT 1";
    $result = $conn->query($sql);
    $data = $result->fetch_assoc();
    return $data;
}

function create_virtual_account(Mysqli $conn, int $account_id, int $amount) {
    $data = null;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sql = "INSERT INTO `transactions` (`account_id`, `amount`) VALUES ($account_id, $amount)";
        $successCondition["transactions"] = $conn->query($sql);
        if($successCondition["transactions"] == TRUE) {
            $transaction_id = $conn->insert_id;
            $virtual_account_number = date("Ymdhms");
            $sql = "INSERT INTO `virtual_accounts` (`transaction_id`, `virtual_account_number`)
                    VALUES ($transaction_id, $virtual_account_number);";
            $successCondition["virtual_accounts"] = $conn->query($sql);

            if($successCondition["virtual_accounts"] == TRUE) $data = get_virtual_account_data($conn, $virtual_account_number);
        }
    }
    return $data;
}