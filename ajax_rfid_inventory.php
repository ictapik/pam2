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
$primaryKey = 'SERNO';

$tnkb = $_GET['tnkb'];

if (isset($_GET['documentno'])) {
    $documentno = $_GET['documentno'];
} else {
    $documentno = "";
}
// $tnkb = "T9239DD";

$table = "SELECT b.NAME, a.SERNO, TO_CHAR(a.CREATED,'DD-MON-YYYY HH24:MI') AS CREATED 
FROM RFID_INVENTORY a
  JOIN M_PRODUCTASSET b ON (b.SERNO = a.SERNO)
  JOIN M_Product p ON (b.M_product_ID=p.M_Product_ID)
WHERE a.serno NOT IN (SELECT serno FROM m_productasset_load)                        
      AND b.MOVEMENTTYPE = 'C+'
      AND b.ISACTIVE = 'Y'
      AND p.PackingType IN 
	         (SELECT DISTINCT p.PackingType
			FROM M_InOutLine iol 
			     INNER JOIN  M_InOut io ON (io.M_InOut_ID=iol.M_InOut_ID)
				INNER JOIN M_Product p ON (iol.M_Product_ID=p.M_Product_ID) 
			WHERE io.TNKB='$tnkb' AND io.DocStatus='DR'
            AND io.DOCUMENTNO LIKE '%$documentno%')";

$columns = array(
    array('db' => 'SERNO', 'dt' => 'serno'),
    array('db' => 'NAME', 'dt' => 'name'),
    array('db' => 'CREATED', 'dt' => 'created'),
);

echo json_encode(
    SSPOCI::simple($_GET, $sql_details, $table, $primaryKey, $columns)
);
