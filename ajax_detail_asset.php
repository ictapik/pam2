<?php

require_once "connect.php";

$m_inout_id = $_POST['m_inout_id'];

$stid = oci_parse(
    $conn,
    "SELECT 
    NAME 
FROM m_productasset 
WHERE m_productasset_id IN (SELECT m_productasset_id FROM m_productasset_load WHERE m_inout_id = '$m_inout_id')"
);

oci_execute($stid);
$data = array();

while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    $data[] = $row['NAME'];
}

echo json_encode($data);
