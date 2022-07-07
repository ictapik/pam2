<?php
require_once "connect.php";

$tnkb = $_GET['tnkb'];

$stid = oci_parse($conn, "SELECT 
MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME AS NAME_PRODUCTASSET, MP.NAME, MP.VALUE, MPAL.M_PRODUCTASSET_LOAD_ID 
FROM M_PRODUCTASSET_LOAD MPAL
JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
WHERE MPAL.MOVEMENTTYPE = 'C+' AND TNKB = '$tnkb'
ORDER BY M_PRODUCTASSET_LOAD_ID DESC");

oci_execute($stid);

while ($row = oci_fetch_assoc($stid)) {
    $res[] = $row;
}

$listPO = json_encode($res);

oci_free_statement($stid);
oci_close($conn);

echo $listPO;
