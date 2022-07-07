<?php

// START sedang dalam perbaikan
//$maintenance = TRUE;
$maintenance = FALSE;

if ($maintenance)
    header("Location: maintenance.php");
// END sedang dalam perbaikan

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$base_url = "http://apik.adyawinsa.com/pam/";
//$base_url="http://".$_SERVER['SERVER_NAME'].dirname($_SERVER["REQUEST_URI"].'?').'/';

require_once "connect.php";
require_once "function.php"; //memanggil kumpulan fungsi
require_once "function2.php"; //memanggil kumpulan fungsi
require_once "function3.php"; //memanggil kumpulan fungsi

// Cek apakah sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] == false) {
    header("Location: login.php");
}

require_once "header.php";

if (!isset($_GET['page']) || empty($_GET['page'])) {
    require_once "dashboard.php";
} else {
    if (file_exists($_GET['page'] . ".php")) {
        require_once $_GET['page'] . ".php";
    } else {
        require_once "404.php";
    }
}

require_once "footer.php";
