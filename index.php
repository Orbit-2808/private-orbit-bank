<?php
$folderName = "web-bank"; // change to folder name
$rootPath = $_SERVER['DOCUMENT_ROOT'] . "/" . $folderName;
include_once($rootPath . "/controller/auth.php");
include_once($rootPath. "/controller/transaction.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <div class="container">
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
        <form action="/auth.php" method="get"><button type="submit" class="btn btn-primary" name="submit" value="Logout">Submit</button></form><br><br/>
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
                        <input type="number" name="amount" id="saving_amount"><br/>
                        <button type="submit" class="btn btn-primary" name="submit" value="Save">Submit</button>
                        <button type="submit" class="btn btn-primary" name="submit" value="Withdraw">Submit</button>
                    </form><br/>
                </td>
                <td style="padding: 0px 20px 0px 20px;">
                    <h3>Transfer Form</h3>
                    <form action="transaction.php" method="post">
                        <input type="hidden" name="sender_account_number" value="<?=$profile["account_number"]?>">
                        <label for="receiver_account_number">Account Number</label><br/>
                        <input type="text" name="receiver_account_number" id="transfer_receiver_account_number"><br/>
                        <label for="amount">Amount</label><br/>
                        <input type="number" name="amount" id="transfer_amount"><br/>
                        <button type="submit" class="btn btn-primary" name="submit" value="Transfer">Submit</button>
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
            <label for="username" class="form-label">username</label><br/>
            <input type="text" class="form-control" name="username" id="login_username"><br/>
            <label for="password" class="form-label">password</label><br/>
            <input type="password" class="form-control" name="password" id="login_password"><br/>
            <button type="submit" class="btn btn-primary" name="submit" value="Login">Submit</button>
        </form>

        <br/>
        <h2>Register</h2>
    
        <form action="/auth.php" method="post">
            <label for="name" class="form-label">name</label><br/>
            <input type="text" class="form-control" name="name" id="register_name"><br/>

            <label for="jalan" class="form-label" id="lbl_jalan">jalan</label><br/>
            <input type="text" class="form-control" name="jalan" id="register_jalan"><br/>

            <label for="provinsi" class="form-label" id="lbl_provinsi">provinsi</label><br/>
            <select class="form-control" name="provinsi" id="register_provinsi"></select><br/>

            <label for="kabupaten" class="form-label" id="lbl_kabupaten">kabupaten/kota</label><br/>
            <select class="form-control" name="kabupaten" id="register_kabupaten"></select><br/>

            <label for="kecamatan" class="form-label" id="lbl_kecamatan">kecamatan</label><br/>
            <input type="text" class="form-control" name="kecamatan" id="register_kecamatan"><br/>

            <label for="kelurahan" class="form-label" id="lbl_kelurahan">kelurahan</label><br/>
            <input type="text" class="form-control" name="kelurahan" id="register_kelurahan"><br/>

            <label for="username" class="form-label">username</label><br/>
            <input type="text" class="form-control" name="username" id="register_username"><br/>
            <label for="email" class="form-label">email</label><br/>
            <input type="text" class="form-control" name="email" id="register_email"><br/>
            <label for="password" class="form-label">password</label><br/>
            <input type="password" class="form-control" name="password" id="register_password"><br/>
            <button type="submit" class="btn btn-primary" name="submit" value="Register">Submit</button>
        </form>
        <?php
                    break;
                }
        ?>
    </div>

    <script src="/register.js"></script>
</body>
</html>