<?php
require_once "connect.php";

$hapus_id = $_POST['hapus_id'];
$serno = $_POST['serno'];

$stid = oci_parse($conn, "DELETE FROM M_PRODUCTASSET_LOAD WHERE M_PRODUCTASSET_LOAD_ID = '$hapus_id'");

oci_execute($stid);

oci_free_statement($stid);
oci_close($conn);
