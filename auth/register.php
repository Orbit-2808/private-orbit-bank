<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/module/auth.php");
switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $sessionCond = register($_POST["name"], $_POST["address"], $_POST["username"], $_POST["email"], $_POST["password"]);
        switch($sessionCond) {
            case SessionCondtion::loggedIn:
                echo "success" . $_SERVER['HTTP_HOST'];
                header("Location: ../");
                die();
                break;
            default;
                echo "fail";
        }

        break;

    default:
        http_response_code(405);
}