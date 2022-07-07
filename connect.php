<?php

$db_host    = "192.168.3.3";
// $db_host    = "localhost";
$db_name    = "plastik";
$db_pass    = "k4r4w4ng";

$conn = oci_connect(
    $db_name,
    $db_pass,
    "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $db_host)
    (PORT = 1521)))(CONNECT_DATA=(SID=XE)))"
);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
