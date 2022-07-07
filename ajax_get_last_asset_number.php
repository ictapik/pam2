<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once "connect.php";

$m_product_id = $_POST['m_product_id'];
$prefix = $_POST['prefix'];

// Ambil nilai m_productasset_id terakhir
$stid = oci_parse(
    $conn,
    "SELECT MAX(name) AS name FROM m_productasset
    WHERE m_product_id='$m_product_id'"
);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);
$name = $row['NAME'];
$last_asset_number = (int) str_replace($prefix, "", $name);
$next_asset_number = $last_asset_number + 1;

echo json_encode(array("next_asset_number" => $next_asset_number));
