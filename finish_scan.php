<?php
require_once "connect.php";

$finish_id = $_POST['finish_id'];
$finish_movemntdate = $_POST['finish_movemntdate'];

// Pindahkan data loading dari table M_PRODUCTASSET_LOAD ke tabel M_PRODUCTASSET_LOG
$stid = oci_parse(
    $conn,
    "INSERT INTO M_PRODUCTASSET_LOG 
    SELECT * FROM M_PRODUCTASSET_LOAD 
    WHERE M_INOUT_ID = '$finish_id'"
);

oci_execute($stid);

// Update field MovementDate pada tabel M_PRODUCTASSET
// $stid = oci_parse($conn, "UPDATE M_PRODUCTASSET SET MOVEMENTDATE = SYSDATE WHERE M_INOUT_ID = '$finish_id'");
$stid = oci_parse(
    $conn,
    "UPDATE M_ProductAsset 
    SET (MovementDate, MovementType, M_Inout_ID)= (SELECT TO_DATE('$finish_movemntdate','YYYY-MM-DD'),'C-', '$finish_id' FROM Dual)
    WHERE M_ProductAsset_ID IN 
    (SELECT M_ProductAsset_ID
	FROM M_ProductAsset_Load 
	WHERE MovementType='C-' AND M_InOut_ID = '$finish_id')"
);

oci_execute($stid);

// Hapus data rfid_inventory
$stid = oci_parse(
    $conn,
    "DELETE FROM RFID_INVENTORY
    WHERE SERNO IN (SELECT SERNO FROM M_PRODUCTASSET_LOAD WHERE MovementType='C-' AND M_INOUT_ID = '$finish_id')"
);

oci_execute($stid);

// Hapus data loading yang telah dipindahkan
$stid = oci_parse(
    $conn,
    "DELETE FROM M_PRODUCTASSET_LOAD 
    WHERE MovementType='C-' AND M_INOUT_ID = '$finish_id'"
);

oci_execute($stid);

oci_free_statement($stid);
oci_close($conn);
