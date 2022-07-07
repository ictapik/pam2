<?php

require_once "connect.php";
// database connection
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.3.3)(PORT = 1521)))(CONNECT_DATA=(SID=XE)))";
// $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = localhost)(PORT = 1521)))(CONNECT_DATA=(SID=XE)))";
$dbusername = "plastik";
$dbpassword = "k4r4w4ng";

$appmode = "dev";

$err_conn = "Gangguan koneksi database. Check koneksi jaringan atau hubungi tim ICT untuk bantuan.";
$err_parse = "Check script query.";
$err_fetch = "Gagal akses data.";

require_once "sspoci.class.php";

// Database server connection information
$sql_details = array(
    'user' => $dbusername,
    'pass' => $dbpassword,
    'db'   => $db,
    'host' => ''
);

$serno = $_GET['serno'];

// Table's primary key
$primaryKey = 'ID';

$table = "SELECT 
    a.m_productasset_log_id AS ID,
    a.serno, 
    NVL (a.tnkb, '-') tnkb, 
    a.devicename,
    a.movementtype, 
    TO_CHAR(a.created,'YYYY-MM-DD HH24:MI') movementdate, 
    NVL (b.documentno, '-') documentno
FROM m_productasset_log a
LEFT JOIN m_inout b ON b.m_inout_id = a.m_inout_id
WHERE serno = '$serno' 
AND ROWNUM <= 50
ORDER BY a.created DESC";

$columns = array(
    array('db' => 'SERNO', 'dt' => 'serno'),
    array('db' => 'DOCUMENTNO', 'dt' => 'documentno'),
    array('db' => 'TNKB', 'dt' => 'tnkb'),
    array('db' => 'DEVICENAME', 'dt' => 'devicename'),
    array('db' => 'MOVEMENTTYPE', 'dt' => 'movementtype'),
    array('db' => 'MOVEMENTDATE', 'dt' => 'movementdate'),
);

echo json_encode(
    SSPOCI::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
