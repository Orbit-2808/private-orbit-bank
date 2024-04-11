<?php
include("controller/auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    /**
     * 
     * Switch view by session login
     * If logged in, show transaction datas
     * else, show login/register menu
     * 
     */
        switch(isLoggedIn()) {
            case SessionCondtion::loggedIn:
    ?>
    <!-- Transaction Datas -->
    <a href="/auth/logout.php"><button>Log out</button></a>
    <table>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>Account Number</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>Balance</td>
            <td>:</td>
            <td></td>
        </tr>
    </table>
    <br/>
    <table>
        <tr>
            <th>No</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Condition</th>
        </tr>
    </table>

    <!-- Login Register Menu -->
    <?php
                break;
            default:
    ?>
    <h2>Log In</h2>
    <form action="/auth/login.php" method="post">
        <label for="username">username</label><br/>
        <input type="text" name="username" id="login_username"><br/><br/>
        <label for="password">password</label><br/>
        <input type="password" name="password" id="login_password"><br/><br/>
        <input type="submit" value="Login">
    </form>

    <br/>
    <h2>Register</h2>
    <form action="/auth/register.php" method="post">
        <label for="username">username</label><br/>
        <input type="text" name="username" id="register_username"><br/><br/>
        <label for="email">email</label><br/>
        <input type="text" name="email" id="email"><br/><br/>
        <label for="password">password</label><br/>
        <input type="password" name="password" id="register_password"><br/><br/>
        <input type="submit" value="Register">
    </form>
    <?php
                break;
            }
    ?>
</body>
</html>