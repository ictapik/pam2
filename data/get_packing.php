<?php

session_start();
require_once "../connect.php";

$term = $_GET['term'];
// $term = 'E200001D9212007711003092';

$stid = oci_parse(
    $conn,
    "SELECT SERNO,  NAME || '-' || SERNO AS SERNONAME 
    FROM M_PRODUCTASSET 
    WHERE SERNO = '$term' 
        AND MOVEMENTTYPE = 'C-'
        AND SERNO NOT IN (SELECT SERNO FROM M_PRODUCTASSET_LOAD)"
);
oci_execute($stid);
oci_fetch_all($stid, $result, null, null, OCI_FETCHSTATEMENT_BY_ROW);

echo json_encode($result);
