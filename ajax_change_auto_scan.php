<?php

require_once "connect.php";

$parameter = $_POST['parameter'];
$auto_scan_value = $_POST['auto_scan_value'];
// $parameter = "auto_scan_ou";
// $auto_scan_value = "false";

//update tabel rfid_parameter
$stid = oci_parse(
    $conn,
    "UPDATE rfid_parameter 
    SET value = '$auto_scan_value'
    WHERE parameter = '$parameter'"
);

oci_execute($stid);
