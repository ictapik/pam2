<?php

$db_host    = "192.168.3.3";
//$db_host    = "localhost";
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

$stid = oci_parse(
    $conn,
    "DELETE FROM rfid_inventory
    WHERE (sysdate - created) > 10/(24*60)
    AND serno NOT IN (
        SELECT serno FROM m_productasset
        WHERE isactive = 'Y'
    )"
);

oci_execute($stid);
$num_deleted = oci_num_rows($stid);

//Save log
$myfile = fopen("C:/xampp/htdocs/pam/auto_delete_log.txt", "a") or die("Unable to open file!");
$txt = date('Y-m-d H:i:s') . " " . $num_deleted . " rows deleted" . PHP_EOL;
fwrite($myfile, $txt);
fclose($myfile);
