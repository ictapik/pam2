<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$base_url = "http://apik.adyawinsa.com/pam/";
$base_url = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER["REQUEST_URI"] . '?') . '/';

//if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
//    header("Location:" . $base_url . "login.php");
//}

unset($_SESSION['login']);
unset($_SESSION['ad_user_id']);
unset($_SESSION['name']);

session_unset();
session_destroy();

header("Location: login.php");
