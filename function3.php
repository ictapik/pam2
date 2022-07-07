<?php

function dashboard_month()
{
    require "connect.php";

    for ($i = 1; $i <= 12; $i++) {
        $month = date('Y-m', strtotime("-$i month")); //number
        $month_txt = date('M Y', strtotime("-$i month")); //text

        $stid = oci_parse(
            $conn,
            "SELECT 
            COUNT(*),
            COUNT(CASE WHEN movementtype = 'C+' THEN MovementType END) count_in,
            COUNT(CASE WHEN movementtype = 'C-' THEN MovementType END) count_out
        FROM m_productasset_log
        WHERE TO_CHAR(movementdate, 'YYYY-MM') = '$month'"
        );

        oci_execute($stid);

        $row = oci_fetch_row($stid);
        $count_all = $row['0'];
        $count_in = $row['1'];
        $count_out = $row['2'];
        $count_inout = $count_in - $count_out;

        //atur warna backgroun cell/kolom
        if ($count_in == 0 and $count_out == 0) {
            $bg_color = "secondary";
        } else if ($count_in < $count_out) {
            $bg_color = "danger"; //warna merah
        } else if ($count_in > $count_out) {
            $bg_color = "success"; //warna hijau
        } else {
            $bg_color = "warning"; //warna kuning
        }
?>
        <td class="text-center month-tooltips bg-<?= $bg_color ?>" dateurl='<?= $month ?>' datecal='<?= strtoupper($month_txt) ?>' datain='<?= $count_in ?>' dataout='<?= $count_out ?>' datadiff='<?= $count_inout ?>'>
            <?= date('M-Y', strtotime("-$i month")); ?>
        </td>
<?php
    }
}
