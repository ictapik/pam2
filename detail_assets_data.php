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

$movementtype = $_GET['movementtype'];
$value = $_GET['value'];

// Table's primary key
$primaryKey = 'SERNO';

$table = "SELECT pa.name, pa.serno, pa.movementtype, pa.isactive,
NVL (TO_CHAR(pa.movementdate, 'DD-MON-YYYY'),'-') movementdate
FROM m_productasset pa
INNER JOIN M_Product p ON (pa.M_Product_ID=p.M_Product_ID)
WHERE MOVEMENTTYPE LIKE '%$movementtype%'
AND p.Value = '$value'
ORDER BY pa.name ASC";

$columns = array(
    array('db' => 'NAME', 'dt' => 'name'),
    array('db' => 'SERNO', 'dt' => 'serno'),
    array('db' => 'MOVEMENTTYPE', 'dt' => 'movementtype'),
    array('db' => 'ISACTIVE', 'dt' => 'isactive'),
    array('db' => 'MOVEMENTDATE', 'dt' => 'movementdate'),
    // array('db' => 'DOCUMENTNO', 'dt' => 'documentno'),
    // array('db' => 'TNKB', 'dt' => 'tnkb'),
    // array('db' => 'MOVEMENTTYPE', 'dt' => 'movementtype'),
);

echo json_encode(
    SSPOCI::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
