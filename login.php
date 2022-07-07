<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$base_url = "http://apik.adyawinsa.com/pam/";
$base_url = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';

require_once "connect.php";

//cek apakah sudah login
if (isset($_SESSION['login'])) {
    header("Location: index.php");
}

//proses ketika email dan password diisi dan menekan tombol login
if (isset($_POST['login'])) {

    $email  = $_POST['email'];
    $password = $_POST['password'];

    $stid = oci_parse($conn, "SELECT * FROM AD_USER WHERE WPMS_Access='PA' AND (email = '$email' OR LOWER(name) = LOWER('$email')) AND password = '$password'");
    oci_execute($stid);

    while ($row = oci_fetch_assoc($stid)) {
        $_SESSION['login'] = true;
        $_SESSION['ad_user_id'] = $row['AD_USER_ID'];
        $_SESSION['name'] = $row['NAME'];
        if ($row['NAME'] == "wahyu.hidayat" || $row['NAME'] == "Abe") {
            $_SESSION['isadmin'] = true;
        } else {
            $_SESSION['isadmin'] = false;
        }
        header("Location: index.php");
    }

    $login_failed = "Email atau password salah!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Packing System</title>
	<link rel="icon" type="image/png" href="./assets/img/favicon.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">

    <style>
        .login-form {
            width: 340px;
            margin: 10px auto;
            font-size: 15px;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav class=" navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <div class="mx-auto order-0">
                <a class="navbar-brand mx-auto" href="#"><b>PACKING SYSTEM</b></a>
                <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
                    <span class="navbar-toggler-icon"></span>
                </button> -->
            </div>
        </div>
    </nav>

    <div class="login-form">

        <?php
        if (isset($login_failed)) {
        ?>
            <div class="alert alert-danger" role="alert">
                <?= $login_failed; ?>
            </div>
        <?php
        }
        ?>

        <form action="login.php" method="post">

            <div class="text-center mb-3">
                <img src="./assets/img/logo.png" width="275px"></img>
            </div>
            <h4 class="text-center font-weight-bold mb-3">LOGIN</h4>
            <div class="form-group">
                <input type="text" name="email" class="form-control" placeholder="Email or Username" required="required">
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="required">
            </div>
			<div class="form-group">
                <input type="checkbox" class="show_password" onclick="showPassword()"> Show password
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn btn-primary btn-block">LOGIN</button>
            </div>
            <!-- <div class="clearfix">
                <label class="float-left form-check-label"><input type="checkbox"> Remember me</label>
                <a href="#" class="float-right">Forgot Password?</a>
            </div> -->
        </form>
        <!-- <p class="text-center"><a href="#">Create an Account</a></p> -->
    </div>

</body>

	<!-- jQuery -->
    <script src="./assets/jquery-3.4.1.min.js"></script>
    <!-- Bootstrap -->
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
	<script>
		function showPassword(){
			var x = document.getElementById("password");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>

</html>