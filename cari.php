<?php
require_once "connect.php";

$serno = $_POST['serno'];

$stid = oci_parse($conn, "SELECT M_PRODUCTASSET_ID, M_PRODUCT_ID FROM M_PRODUCTASSET WHERE SERNO = '$serno'");

oci_execute($stid);

while ($row = oci_fetch_assoc($stid)) {
    $res[] = $row;
}

$listPO = json_encode($res);

oci_free_statement($stid);
oci_close($conn);

echo $listPO;
