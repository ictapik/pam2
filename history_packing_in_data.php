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

// Table's primary key
$primaryKey = 'TNKB';

$table = "SELECT 
DISTINCT tnkb, to_char(trunc(movementdate), 'YYYY-MM-DD') movementdate, to_char(trunc(movementdate), 'DD-MM-YYYY') movementdate_ind
FROM m_productasset_log 
WHERE movementtype = 'C+'";

$columns = array(
    array('db' => 'TNKB', 'dt' => 'tnkb'),
    array('db' => 'MOVEMENTDATE', 'dt' => 'movementdate'),
    array('db' => 'MOVEMENTDATE_IND', 'dt' => 'movementdate_ind'),
);

echo json_encode(
    SSPOCI::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
