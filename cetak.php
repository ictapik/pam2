<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$base_url = "http://apik.adyawinsa.com/pam/";
require_once "connect.php";

require_once "./dompdf/autoload.inc.php";

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$history = false;
if (isset($_SERVER['HTTP_REFERER'])) {
  $previous_page = $_SERVER['HTTP_REFERER'];
  $previous_page = explode("=", $previous_page);
  if ($previous_page[1] == "history_packing_out") {
    $history = true;
  }
} else {
  $history = true;
}

$m_inout_id = $_GET['m_inout_id'];

//menampilkan gambar
$path = './assets/img/logo.jpeg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$html = "
<html>
    <head>
    <head>
    <!-- Required meta tags -->
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate'>
    <meta http-equiv='Pragma' content='no-cache'>
    <meta http-equiv='Expires' content='0'>
    <!-- Bootstrap CSS -->
    <link rel='stylesheet' href='./assets/bootstrap/css/bootstrap.min.css'>
    <link rel='stylesheet' href='./assets/style.css'>
    <!-- Datatable -->
    <link rel='stylesheet' href='./assets/datatables/css/jquery.dataTables.min.css'>
    <!-- jQuery -->
    <script src='./assets/jquery-3.4.1.min.js'></script>
    <!-- Bootstrap -->
    <script src='./assets/bootstrap/js/bootstrap.min.js'></script>
    <!-- Datatable -->
    <script src='./assets/datatables/js/jquery.dataTables.min.js'></script>
    <script src='./ssets/datatables/js/dataTables.bootstrap4.min.js'></script>
    <style>
    .table-bordered>tbody>tr>td,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
        border: 1px solid #000000;
    }
    </style>
</head>

<body>";

$stid = oci_parse($conn, "SELECT documentno, name, tnkb, TRUNC(movementdate) movementdate
    From M_inOut mio
    JOIN C_BPartner cbp ON mio.C_BPartner_ID = cbp.C_BPartner_ID
    WHERE M_InOut_ID = '$m_inout_id'");

// Perform the logic of the query
oci_execute($stid);
$row_header = oci_fetch_array($stid, OCI_ASSOC);

$html .= "
<table style='font-size:12px' width='100%'>
  <tr>
    <th colspan='2'><u>SURAT JALAN PACKING</u></th>
    <td rowspan='5'></td>
    <th rowspan='5' width='150px' style='text-align:center; font-size:11px' valign='top'>
      <img src='" . $base64 . "' width='130px'><br>
      PT. ADYAWINSA PLASTICS INDUSTRY
    </th>    
  </tr>
  <tr>s
    <th width='60px'>DN NO</th>
    <td>: " . $row_header['DOCUMENTNO'] . "</td>
  </tr>
  <tr>
    <th>DATE</th>
    <td>: " . date_format(date_create($row_header['MOVEMENTDATE']), 'd-M-Y') . "</td>
  </tr>
  <tr>
    <th>SHIP TO</th>
    <td>: " . $row_header['NAME'] . "</td>
  </tr>
  <tr>
    <th>TNKB</th>
    <td>: " . $row_header['TNKB'] . "</td>
  </tr>
</table>";

$html .= "
          <table class='table table-bordered mt-4'>
          <thead>
            <tr style='font-size:12px'>
              <th scope='col' width='10px'>NO</th>
              <th scope='col' width='125px'>TIPE PACKING</th>
              <th scope='col' width='55px'>QTY BOX</th>
              <th scope='col'>SERIAL NO/ID NO</th>
            </tr>
          </thead>
		  <tbody>";

// Prepare the statement
$stid = oci_parse($conn, "SELECT 
MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME, MP.NAME NAME_PRODUCT, MP.VALUE, MPAL.M_PRODUCTASSET_LOG_ID 
FROM M_PRODUCTASSET_LOG MPAL
JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
WHERE MPAL.M_INOUT_ID = '$m_inout_id'
ORDER BY MPA.NAME ASC");

oci_execute($stid);

// $tags = array(array());

while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
  // if (!array_key_exists($row['NAME_PRODUCT'], $tags)) {
  $tags[htmlspecialchars($row['NAME_PRODUCT'], ENT_NOQUOTES, 'UTF-8')][] = htmlspecialchars($row['NAME'], ENT_NOQUOTES, 'UTF-8');
  // } else {
  // $tags[htmlspecialchars($row['NAME_PRODUCT'], ENT_NOQUOTES, 'UTF-8')][] = htmlspecialchars($row['NAME'], ENT_NOQUOTES, 'UTF-8');
  // }
}

$no = 1;
$jumlah = 0;

foreach ($tags as $key => $value) {

  $html .= "<tr style='font-size:12px'>";

  $html .= "<td style='text-align:center'>$no</td>";

  $html .= "<td>$key</td>";

  $html .= "<td style='text-align:center'>" . count($tags[$key]) . "</td>";

  $html .= "<td style='font-size:10px'>" . implode(", ", $tags[$key]) . "</td>";

  $html .= "</tr>";


  $jumlah = $jumlah + count($tags[$key]);
  $no = $no + 1;
}

$html .= "<tr style='font-size:12px'>";
$html .= "<th colspan='2' style='text-align:center'>JUMLAH</th>";
$html .= "<th style='text-align:center'>$jumlah</th>";
$html .= "<th></th>";
$html .= "</tr>";

$html .= "</tbody>
</table>";

$html .= "<font style='font-size:9.5px'>";

if ($history) {
  $html .= "Reprinted Date : ";
} else {
  $html .= "Printed Date : ";
}

$html .= date('D, d-M-Y H:i:s A');
$html .= " ~ ";

if ($history) {
  $html .= "Reprinted By : ";
} else {
  $html .= "Printed By : ";
}

$html .= $_SESSION['name'];
$html .= "</font>";

// <center><font style='font-size:12px'>Karawang, " . date('d-M-Y') . "</font></center>

$html .= "<table class='table table-borderless'>
<tr>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>Delivered By,</font>
</td>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>Checked By,</font>
</td>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>Received By,</font>
</td>
</tr>
<tr>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>(.................................)<br>Driver</font>
</td>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>(.................................)<br>Security</font>
</td>
<td style='height:10px; text-align:center'>
<font style='font-size:12px'>(.................................)<br>Customer</font>
</td>
</tr>
</table>";

$html .= "</body>
</html>";

$dompdf->setBasePath(realpath('../assets/bootstrap/css/bootstrap.css'));

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'potrait');
$dompdf->render();
$dompdf->stream("PACKING OUT - DN " . $row_header['DOCUMENTNO'] . ".pdf", array('Attachment' => 0));
