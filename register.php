<?php
include_once("controller/auth.php");

// // look json version
// header('Content-Type: application/json');
// echo json_encode($_POST);

$tempAddress = implode(";", $_POST["address"]);
unset($_POST["address"]);
$_POST["address"] = $tempAddress;
// echo json_encode($_POST);

$sessionCond = register($_POST["name"], $_POST["address"], $_POST["username"], $_POST["email"], $_POST["password"]);
if ($sessionCond == SessionCondtion::loggedIn) {
    echo "success" . $_SERVER['HTTP_HOST'];
    header("Location: ../");
    die();
} else {
    echo "fail";
}