<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/controller/auth.php");

// get submit value
if ($_SERVER["REQUEST_METHOD"] == "POST") $submitValue = $_POST["submit"];
else if ($_SERVER["REQUEST_METHOD"] == "GET") $submitValue = $_GET["submit"];

// validate http method
$allowHttpMethod = true;
switch ($submitValue) {
    case "Login":
        if ($_SERVER["REQUEST_METHOD"] == "GET") $allowHttpMethod = false;
        break;
    case "Logout":
        if ($_SERVER["REQUEST_METHOD"] == "POST") $allowHttpMethod = false;
        break;
    case "Register":
        if ($_SERVER["REQUEST_METHOD"] == "GET") $allowHttpMethod = false;
        break;
}

// run if allowed
if (!$allowHttpMethod) {
    http_response_code(405);
    die();
}

switch ($submitValue) {
    case "Login":
        $sessionCond = login($_POST["username"], $_POST["password"]);
        if ($sessionCond == SessionCondtion::loggedIn) {
            echo "success" . $_SERVER['HTTP_HOST'];
            header("Location: ../");
            die();
        } else {
            echo "fail";
        }
        break;
    case "Logout":
        $sessionCond = isLoggedIn();
        if ($sessionCond == SessionCondtion::loggedIn) {
            logout();
            header("Location: /");
            die();
        } else {
            echo "Cannot logout";
        }
        break;
    case "Register":
        $sessionCond = register($_POST["name"], $_POST["address"], $_POST["username"], $_POST["email"], $_POST["password"]);
        if ($sessionCond == SessionCondtion::loggedIn) {
            echo "success" . $_SERVER['HTTP_HOST'];
            header("Location: ../");
            die();
        } else {
            echo "fail";
        }
        break;
    default:
        echo "Not Accepted";
        break;
}