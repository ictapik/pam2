<?php
require_once "connect.php";

$tnkb = $_POST['tnkb'];
$m_inout_id = $_POST['m_inout_id'];
$m_productasset_id = $_POST['m_productasset_id'];
$m_product_id = $_POST['m_product_id'];
$serno = $_POST['serno'];
$created = $_SESSION['ad_user_id'];

$stid = oci_parse(
    $conn,
    "INSERT INTO M_PRODUCTASSET_LOAD
    (M_PRODUCTASSET_LOAD_ID, AD_CLIENT_ID, AD_ORG_ID, CREATED, CREATEDBY, M_INOUT_ID, M_PRODUCTASSET_ID, 
    M_PRODUCT_ID, SERNO, UPDATED, UPDATEDBY, MOVEMENTTYPE, MOVEMENTDATE, TNKB) 
    VALUES 
    (M_PRODUCTASSET_LOG_SEQ.NEXTVAL, 1000000, 0, sysdate, 100, '$m_inout_id', '$m_productasset_id', 
    '$m_product_id', '$serno', sysdate, 100,'C-', (SELECT MOVEMENTDATE FROM M_INOUT WHERE M_INOUT_ID = '$m_inout_id'), '$tnkb')"
);

oci_execute($stid);

oci_free_statement($stid);
oci_close($conn);
