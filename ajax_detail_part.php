<?php

require_once "connect.php";

$m_inout_id = $_POST['m_inout_id'];

$stid = oci_parse(
    $conn,
    "SELECT 
    p.PackingType, P.Value PartNo, p.Name PartName, p.UnitsPerPack, SUM(iol.MovementQty) QtyPart, SUM(ROUND(iol.MovementQty/p.UnitsPerPack,0)) QtyPacking
 FROM M_InOutLine iol 
 INNER JOIN M_Product p ON (iol.M_Product_ID=p.M_Product_ID) 
 WHERE iol.M_InOut_ID='$m_inout_id'
 GROUP BY p.PackingType, p.Value, p.Name, p.UnitsPerPack
 ORDER BY p.PackingType"
);

oci_execute($stid);

$row = oci_fetch_array($stid, OCI_ASSOC);

echo json_encode($row);
