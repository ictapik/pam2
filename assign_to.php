<?php

require_once "connect.php";

$tnkb  = $_POST['tnkb'];
$m_inout_id  = $_POST['m_inout_id'];
$s_cb_serno = $_POST['s_cb_serno'];

if (!empty($s_cb_serno)) {
    $stid = oci_parse(
        $conn,
        "INSERT INTO M_PRODUCTASSET_LOAD 
            (M_PRODUCTASSET_LOAD_ID, AD_CLIENT_ID, AD_ORG_ID, CREATED, CREATEDBY, M_INOUT_ID, M_PRODUCTASSET_ID, 
            M_PRODUCT_ID, SERNO, UPDATED, UPDATEDBY, MOVEMENTTYPE, MOVEMENTDATE, TNKB, DEVICENAME)         
        SELECT
            M_PRODUCTASSET_LOG_SEQ.NEXTVAL, 1000000, 0, sysdate, 100, '$m_inout_id', pa.m_productasset_id, 
            pa.m_product_id, pa.serno, sysdate, 100,'C-',
            (SELECT io.MOVEMENTDATE FROM M_INOUT io WHERE M_INOUT_ID = '1010001') MovementDate, '$tnkb' tnbk, r.devicename
        FROM RFID_Inventory r
        INNER JOIN M_PRODUCTASSET pa ON (r.serno=pa.serno)
        WHERE pa.serno IN ($s_cb_serno)"
    );
    oci_execute($stid);
}

echo json_encode(
    array(
        "status" => true
    )
);
