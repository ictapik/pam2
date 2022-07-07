<?php
require_once "connect.php";

$m_inout_id = $_GET['m_inout_id'];

$stid = oci_parse($conn, "SELECT 
MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME AS NAME_PRODUCTASSET, MP.NAME, MP.VALUE, MPAL.M_PRODUCTASSET_LOAD_ID 
FROM M_PRODUCTASSET_LOAD MPAL
LEFT JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
LEFT JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
WHERE MPAL.M_INOUT_ID = '$m_inout_id'
ORDER BY M_PRODUCTASSET_LOAD_ID DESC");



oci_execute($stid);

$res = array();

while ($row = oci_fetch_assoc($stid)) {
    $res[] = $row;
}

$listPO = json_encode($res);

oci_free_statement($stid);
oci_close($conn);

echo $listPO;
