<?php

require_once "connect.php";

$serno = $_POST['serno'];

$stid = oci_parse(
    $conn,
    "SELECT movementtype
    FROM m_productasset
    WHERE serno = '$serno'"
);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);
$last_movementtype = $row['MOVEMENTTYPE'];
$last_movementtype == "C+" ? $new_movementtype = "C-" : $new_movementtype = "C+";

$stid = oci_parse(
    $conn,
    "UPDATE m_productasset
    SET movementtype = '$new_movementtype'
    WHERE serno = '$serno'"
);
oci_execute($stid);

$stid2 = oci_parse($conn, "SELECT M_PRODUCTASSET_ID, M_PRODUCT_ID FROM M_PRODUCTASSET WHERE SERNO = '$serno'");
oci_execute($stid2);

while ($row = oci_fetch_assoc($stid2)) {

    $m_productasset_id  = $row['M_PRODUCTASSET_ID'];
    $m_product_id       = $row['M_PRODUCT_ID'];

    $stid3 = oci_parse(
        $conn,
        "INSERT INTO M_PRODUCTASSET_LOG
            (M_PRODUCTASSET_LOG_ID, AD_CLIENT_ID, AD_ORG_ID, CREATED, CREATEDBY, M_PRODUCTASSET_ID, 
            M_PRODUCT_ID, SERNO, UPDATED, UPDATEDBY, MOVEMENTTYPE, MOVEMENTDATE, DEVICENAME) 
            VALUES 
            (M_PRODUCTASSET_LOG_SEQ.NEXTVAL, 1000000, 0, sysdate, 100, '$m_productasset_id', 
            '$m_product_id', '$serno', sysdate, 100,'$new_movementtype', sysdate, 'ADJUSTMENT')"
    );

    oci_execute($stid3);

    /**
     * SIMPAN DATA DISINI
     */
}

echo json_encode(array("status" => true));
