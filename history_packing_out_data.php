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
$primaryKey = 'ID';

$table = "SELECT 
MIO.M_INOUT_ID AS ID, MIO.DOCUMENTNO, CBP.NAME, MIO.TNKB, TO_CHAR(MIO.MOVEMENTDATE, 'YYYY-MM-DD') CREATED
FROM M_INOUT MIO
JOIN C_BPARTNER CBP ON MIO.C_BPARTNER_ID = CBP.C_BPARTNER_ID
WHERE MIO.M_INOUT_ID IN (SELECT DISTINCT M_INOUT_ID FROM M_PRODUCTASSET_LOG)";

$columns = array(
    array('db' => 'DOCUMENTNO', 'dt' => 'documentno'),
    array('db' => 'NAME', 'dt' => 'name'),
    array('db' => 'TNKB', 'dt' => 'tnkb'),
    array('db' => 'CREATED', 'dt' => 'created'),
    array('db' => 'ID', 'dt' => 'id'),
);

echo json_encode(
    SSPOCI::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
