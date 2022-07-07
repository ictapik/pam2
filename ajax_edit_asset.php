<?php

require_once "connect.php";

$asset_name = $_POST['asset_name'];
$asset_serno = $_POST['asset_serno'];
// $asset_name = "KWK001";
// $asset_serno = "SERNO-PERCOBAAN";

$stid = oci_parse(
    $conn,
    "UPDATE m_productasset
    SET serno = '$asset_serno'
    WHERE name = '$asset_name'"
);

oci_execute($stid);

// echo json_encode(array("status" => true));
