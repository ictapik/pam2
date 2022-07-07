<?php

require_once "connect.php";

$serno = $_POST['serno'];

$stid = oci_parse(
    $conn,
    "SELECT isactive
    FROM m_productasset
    WHERE serno = '$serno'"
);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);
$last_isactive = $row['ISACTIVE'];
$last_isactive == "Y" ? $new_isactive = "N" : $new_isactive = "Y";

$stid = oci_parse(
    $conn,
    "UPDATE m_productasset
    SET isactive = '$new_isactive'
    WHERE serno = '$serno'"
);
oci_execute($stid);

echo json_encode(array("status" => true));
