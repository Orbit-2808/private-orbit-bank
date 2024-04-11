<?php
switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        include_once($_SERVER['DOCUMENT_ROOT'] . "/controller/auth.php");
        $sessionCond = isLoggedIn();
        switch($sessionCond) {
            case SessionCondtion::loggedIn:
                echo "success";
                logout();
                header("Location: ../");
                die();
                break;
            default:
                echo "cannot log out";
        }

        break;

    default:
        http_response_code(405);
}