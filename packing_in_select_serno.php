<?php
require_once "connect.php";

$serno = $_POST['searchTerm'];

$stid = oci_parse(
    $conn,
    "SELECT SERNO,  NAME || '-' || SERNO AS SERNONAME 
    FROM M_PRODUCTASSET 
    WHERE (SERNO LIKE UPPER('%$serno%') OR NAME LIKE UPPER('%$serno%')) AND MOVEMENTTYPE = 'C-'
    AND SERNO NOT IN (SELECT SERNO FROM M_PRODUCTASSET_LOAD)"
);

oci_execute($stid);

while ($row = oci_fetch_assoc($stid)) {
    $res[] = array("id" => $row['SERNO'], "text" => $row['SERNONAME']);
}

$listPO = json_encode($res);

oci_free_statement($stid);
oci_close($conn);

echo $listPO;
