<?php

require_once "connect.php";

if (!empty($_POST['cb_serno'])) {
    $cb_serno = $_POST['cb_serno'];

    foreach ($cb_serno as $serno) {
        $stid = oci_parse($conn, "DELETE FROM RFID_INVENTORY WHERE SERNO = '$serno'");
        oci_execute($stid);
    }

    echo json_encode(array(
        "status" => true
    ));
}
