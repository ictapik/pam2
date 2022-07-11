<?php
session_start();
require_once "connect.php";

$asset_name = $_POST['asset_name'];
$asset_serno = $_POST['asset_serno'];
$ad_user_id = $_SESSION['ad_user_id'];

$stid = oci_parse(
    $conn,
    "UPDATE m_productasset
    SET serno = '$asset_serno', 
        updated = sysdate,
        updatedby = $ad_user_id
    WHERE name = '$asset_name'"
);

oci_execute($stid);
