<?php

/**
 * SCAN RFID
 * Di halamn ini akan dilakukan proses:
 * 1. Mencari data di tabel RFID_INVENTORY, apakah ada data atau tidak (packing in)
 * 2. Jika ada data maka cari data (tabel M_PRODUCTASSET berdasarkan @var serno):
 *  a. m_productasset_id
 *  b. m_product_id
 * data tersebut dan beberapa data lainnya akan dipindah ke tabel M_PRODUCTASSET_LOAD
 * 
 * Data yang akan disimpan yaitu:
 * @var tnkb
 * @var m_inout_id
 * @var m_productasset_id
 * @var m_product_id
 * @var serno
 */

require_once "connect.php";

$tnkb  = $_POST['tnkb'];
$rfid  = $_POST['rfid'];

if ($rfid == "All") {
  $rfid = "RFID";
}

$stid = oci_parse(
  $conn,
  "SELECT a.SERNO, a.DEVICENAME
  FROM RFID_INVENTORY a
  JOIN M_PRODUCTASSET b ON b.SERNO = a.SERNO
  WHERE b.MOVEMENTTYPE = 'C-'
	  AND b.ISACTIVE = 'Y'
    AND a.SERNO NOT IN (SELECT SERNO FROM M_PRODUCTASSET_LOAD)
    AND DEVICENAME LIKE '%$rfid%'"
);
oci_execute($stid);

while ($row = oci_fetch_assoc($stid)) {

  $serno = $row['SERNO'];
  $devicename = $row['DEVICENAME'];

  $stid2 = oci_parse($conn, "SELECT M_PRODUCTASSET_ID, M_PRODUCT_ID FROM M_PRODUCTASSET WHERE SERNO = '$serno'");
  oci_execute($stid2);

  while ($row = oci_fetch_assoc($stid2)) {

    $m_productasset_id  = $row['M_PRODUCTASSET_ID'];
    $m_product_id       = $row['M_PRODUCT_ID'];

    $stid3 = oci_parse(
      $conn,
      "INSERT INTO M_PRODUCTASSET_LOAD
            (M_PRODUCTASSET_LOAD_ID, AD_CLIENT_ID, AD_ORG_ID, CREATED, CREATEDBY, M_PRODUCTASSET_ID, 
            M_PRODUCT_ID, SERNO, UPDATED, UPDATEDBY, MOVEMENTTYPE, MOVEMENTDATE, TNKB, DEVICENAME) 
            VALUES 
            (M_PRODUCTASSET_LOG_SEQ.NEXTVAL, 1000000, 0, sysdate, 100, '$m_productasset_id', 
            '$m_product_id', '$serno', sysdate, 100,'C+', sysdate, '$tnkb', '$devicename')"
    );

    oci_execute($stid3);
  }
}

echo json_encode(array(
  "status" => true
));
