<?php

include("controller/auth.php");
include("controller/transaction.php");

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
            $profile = getProfile($_SESSION["username"]);
            $balances = getBalanceRecords($profile["account_number"]);
    ?>
    
    <!-- Account -->
    <form action="/auth.php" method="get"><input type="submit" name="submit" value="Logout"></form><br><br/>
    <table>
        <tr>
            <td style="padding: 0px 20px 0px 0px;">Name</td>
            <td style="padding: 0px 20px 0px 20px;">:</td>
            <td style="padding: 0px 20px 0px 20px;"><?=$profile["name"]?></td>
        </tr>
        <tr>
            <td style="padding: 0px 20px 0px 0px;">Address</td>
            <td style="padding: 0px 20px 0px 20px;">:</td>
            <td style="padding: 0px 20px 0px 20px;"><?=$profile["address"]?></td>
        </tr>
        <tr>
            <td style="padding: 0px 20px 0px 0px;">Email</td>
            <td style="padding: 0px 20px 0px 20px;">:</td>
            <td style="padding: 0px 20px 0px 20px;"><?=$profile["email"]?></td>
        </tr>
        <tr>
            <td style="padding: 0px 20px 0px 0px;">Account Number</td>
            <td style="padding: 0px 20px 0px 20px;">:</td>
            <td style="padding: 0px 20px 0px 20px;"><?=$profile["account_number"]?></td>
        </tr>
    </table>
    <br/>

    <table border="1">
        <tr>
            <td style="padding: 0px 20px 0px 20px;">
                <h3>Saving Form</h3>
                <form action="transaction.php" method="post">
                    <input type="hidden" name="account_number" value="<?=$profile["account_number"]?>">
                    <label for="amount">Amount</label><br/>
                    <input type="number" name="amount" id="saving_amount"><br/><br/>
                    <input type="submit" name="submit" value="Save">
                    <input type="submit" name="submit" value="Withdraw">
                </form><br/>
            </td>
            <td style="padding: 0px 20px 0px 20px;">
                <h3>Transfer Form</h3>
                <form action="transaction.php" method="post">
                    <input type="hidden" name="sender_account_number" value="<?=$profile["account_number"]?>">
                    <label for="receiver_account_number">Account Number</label><br/>
                    <input type="text" name="receiver_account_number" id="transfer_receiver_account_number"><br/><br/>
                    <label for="amount">Amount</label><br/>
                    <input type="number" name="amount" id="transfer_amount"><br/><br/>
                    <input type="submit" name="submit" value="Transfer">
                </form><br/>
            </td>
        </tr>
    </table>
    <br/>

    <!-- Balances -->
    <table border="1">
        <tr>
            <th style="padding: 0px 20px 0px 20px;">No</th>
            <th style="padding: 0px 20px 0px 20px;">Date</th>
            <th style="padding: 0px 20px 0px 20px;">Debit</th>
            <th style="padding: 0px 20px 0px 20px;">Credit</th>
            <th style="padding: 0px 20px 0px 20px;">Balance</th>
        </tr>
        
        <?php
        $idx = 1;
        foreach ($balances as $balance) {
        ?>
        <tr>
            <td style="padding: 0px 20px 0px 20px;"><?=$idx?></td>
            <td style="padding: 0px 20px 0px 20px;"><?=$balance["record_date"]?></td>
            <td style="padding: 0px 20px 0px 20px;">Rp. <?=number_format($balance["debit"], 2, ",", ".")?></td>
            <td style="padding: 0px 20px 0px 20px;">Rp. <?=number_format($balance["credit"], 2, ",", ".")?></td>
            <td style="padding: 0px 20px 0px 20px;">Rp. <?=number_format($balance["balance"], 2, ",", ".")?></td>
        </tr>
        <?php
        }
        ?>
    </table>

    <!-- Login Register Menu -->
    <?php
                break;
            default:
    ?>
    <h2>Log In</h2>
    <form action="/auth.php" method="post">
        <label for="username">username</label><br/>
        <input type="text" name="username" id="login_username"><br/><br/>
        <label for="password">password</label><br/>
        <input type="password" name="password" id="login_password"><br/><br/>
        <input type="submit" name="submit" value="Login">
    </form>

    <br/>
    <h2>Register</h2>
    <form action="/auth.php" method="post">
        <label for="name">name</label><br/>
        <input type="text" name="name" id="register_name"><br/><br/>
        <label for="address">address</label><br/>
        <input type="text" name="address" id="register_address"><br/><br/>
        <label for="username">username</label><br/>
        <input type="text" name="username" id="register_username"><br/><br/>
        <label for="email">email</label><br/>
        <input type="text" name="email" id="register_email"><br/><br/>
        <label for="password">password</label><br/>
        <input type="password" name="password" id="register_password"><br/><br/>
        <input type="submit" name="submit" value="Register">
    </form>
    <?php
                break;
            }
    ?>
</body>
</html>