<?php
switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        include_once($_SERVER['DOCUMENT_ROOT'] . "/controller/auth.php");
        $sessionCond = login($_POST["username"], $_POST["password"]);
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