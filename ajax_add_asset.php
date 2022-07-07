<?php
// session_start();

require_once "connect.php";

$asset_productid = $_POST['asset_productid'];
$createdby = $_POST['asset_createdby'];
$asset_prefix = $_POST['asset_prefix'];
$asset_suffix = $_POST['asset_suffix'];
$asset_start = $_POST['asset_start'];
$asset_end = $_POST['asset_end'];

if ($asset_end == "" || $asset_end < $asset_start) {
    $count = 1;
    $asset_end = $asset_start;
} else {
    $count = $asset_end - ($asset_start - 1);
}

// Ambil nilai m_productasset_id terakhir
$stid = oci_parse(
    $conn,
    "SELECT CurrentNext
    FROM AD_Sequence
    WHERE Name = 'M_ProductAsset'
    AND IsActive = 'Y'
    AND IsTableID = 'Y'
    AND IsAutoSequence = 'Y'
    FOR UPDATE OF CurrentNext"
);

oci_execute($stid);
$row = oci_fetch_array($stid, OCI_ASSOC);
$m_productasset_id = $row['CURRENTNEXT'];

// $values = array();

// Looping untuk menentukan berapa banyak data yang akan ditambahkan
for ($i = $asset_start; $i <= $asset_end; $i++) {
    $asset_name = create_asset_name($asset_prefix, $asset_suffix, $i);

    $stid2 = oci_parse(
        $conn,
        "INSERT INTO m_productasset (m_productasset_id, m_product_id, ad_client_id, ad_org_id, created, createdby, isactive, movementtype, name, serno, updated, updatedby) VALUES ('$m_productasset_id', '$asset_productid', 1000000, 0, sysdate, '$createdby', 'N', 'C+', '$asset_name', '$asset_name', sysdate, '$createdby')"
    );
    oci_execute($stid2);

    $m_productasset_id = $m_productasset_id + 1;

    // $values[] = " INSERT INTO m_productasset (m_productasset_id, m_product_id, ad_client_id, ad_org_id, created, createdby, isactive, movementtype, name, serno, updated, updatedby) VALUES ('$m_productasset_id', '$asset_productid', 1000000, 0, sysdate, '$createdby', 'N', 'C+', '$asset_name', '$asset_name', sysdate, '$createdby') ";


    // $values = array();
    // for ($x = 0; $x < $ParticiDetails; $x++) {
    //     $values[] = "('$field1','$field2','$field3','$field4')";
    // }

    // $sql = "INSERT INTO table (column1,column2,column3,column4) VALUES";
    // $sql .= implode(",", $values);
    // echo "$sql";
}

// $sql = " INSERT INTO m_productasset (m_productasset_id, m_product_id, ad_client_id, ad_org_id, created, createdby, isactive, movementtype, name, serno, updated, updatedby) VALUES";
// $sql .= implode(" ", $values);

// echo json_encode(array("data" => $sql));

// // Simpan data asset baru
// $stid2 = oci_parse(
//     $conn,
//     "INSERT ALL $sql SELECT * FROM dual"
// );

// oci_execute($stid2);

// // Update sequence m_productasset_id
$stid3 = oci_parse(
    $conn,
    "UPDATE AD_Sequence 
    SET CurrentNext = CurrentNext + '$count',
    Updated = SysDate
    WHERE Name = 'M_ProductAsset'"
);

oci_execute($stid3);
echo json_encode(array("status" => true));

// fungsi untuk membuat nama asset
function create_asset_name($prefix, $suffix, $number)
{
    $asset_number = (int) substr($number, -$suffix, $suffix);
    $prefix = $prefix;
    $asset_name = $prefix . sprintf("%0" . $suffix . "s", $asset_number);
    return $asset_name;
}
