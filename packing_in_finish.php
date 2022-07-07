<?php
require_once "connect.php";

$finish_id = $_POST['finish_id'];

// Pindahkan data loading dari table M_PRODUCTASSET_LOAD ke tabel M_PRODUCTASSET_LOG
$stid = oci_parse(
    $conn,
    "INSERT INTO M_PRODUCTASSET_LOG 
    SELECT * FROM M_PRODUCTASSET_LOAD 
    WHERE Tnkb = '$finish_id'"
);

oci_execute($stid);

// Update field MovementDate pada tabel M_PRODUCTASSET
// $stid = oci_parse($conn, "UPDATE M_PRODUCTASSET SET MOVEMENTDATE = SYSDATE WHERE M_INOUT_ID = '$finish_id'");
$stid = oci_parse(
    $conn,
    "UPDATE M_ProductAsset 
    SET (MovementDate, MovementType, M_Inout_ID) = (SELECT sysdate, 'C+', '' FROM DUAL)
    WHERE Serno IN 
    (SELECT Serno FROM M_ProductAsset_Load WHERE MovementType = 'C+' AND Tnkb = '$finish_id')"
);

oci_execute($stid);

// Hapus data rfid_inventory
$stid = oci_parse(
    $conn,
    "DELETE FROM RFID_INVENTORY WHERE SERNO IN (SELECT SERNO FROM M_PRODUCTASSET_LOAD WHERE MovementType='C+' AND Tnkb = '$finish_id')"
);

oci_execute($stid);

// Hapus data loading yang telah dipindahkan
$stid = oci_parse(
    $conn,
    "DELETE FROM M_ProductAsset_Load WHERE MovementType = 'C+' AND Tnkb = '$finish_id'"
);

oci_execute($stid);

oci_free_statement($stid);
oci_close($conn);
