<?php
/**
 * Auth adalah fitur web.
 * Cukup gunakan apabila diperlukan saja
 * Hindari pengubahan pada fitur ini demi keamanan data
 */
include_once($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");

enum SessionCondtion {
    case loggedIn;
    case unLoggedIn;
    case wrong;
};

session_start();

function isLoggedIn() {
    if (isset($_SESSION["condition"])) $sessionCondition = $_SESSION["condition"];
    else $sessionCondition = SessionCondtion::unLoggedIn;
    return $sessionCondition;
}

function login($username, $password) {
    if ($_SESSION["condition"] != SessionCondtion::loggedIn) {
        $conn = db_connect();
    
        $query = "SELECT users.* FROM users INNER JOIN accounts ON users.user_id = accounts.user_id
                    WHERE username = '$username' AND password = SHA2('$password', 224)";
    
        $result = mysqli_query($conn, $query);
    
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION["username"] = $row["username"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["condition"] = SessionCondtion::loggedIn;
        } else {
            $_SESSION["condition"] = SessionCondtion::wrong;
            session_destroy();
        }
    
        mysqli_close($conn);
    } else {
        $_SESSION["condition"] = SessionCondtion::wrong;
        session_destroy();
    }

    return $_SESSION["condition"];
}

function logout() {
    session_destroy();
    session_start();
    $_SESSION["condition"] = SessionCondtion::unLoggedIn;
}

function register($name, $address, $username, $email, $password) {
    if ($_SESSION["condition"] != SessionCondtion::loggedIn) {
        $conn = db_connect();
        
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', SHA2('$password', 224))";
        $result = mysqli_query($conn, $query);

        if($result) {
            $userId = mysqli_insert_id($conn);
            echo($userId);

            $accountNumber = $userId; // just fake it, so i use user id

            $query = "INSERT INTO accounts (user_id, name, address, account_number) VALUES ($userId, '$name', '$address', '$accountNumber')";
            $result = mysqli_query($conn, $query);
        }
        mysqli_close($conn);
    }
    
    $sessionCondition = login($username, $password);
    return $sessionCondition;
}