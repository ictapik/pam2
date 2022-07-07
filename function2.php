<?php

function dashboard_cal_2($start, $end, $packing_type = null)
{
    // START
    // Query jumlah packing in, packing out dan selisih antara keduanya.
    // Hasil query akan ditampilkan di tabel berbentuk kalender untk mengetahui-
    // packing in, packing out dan selisih antara keduanya di masing2 tanggal selama bulan berjalan.
    // Tambahan (dibawah tabel): Progressbar presentase antara packing in dan packing out.    
    require "connect.php";

    if ($packing_type == null) {
        $packing_type = "";
    }

    $stid = oci_parse(
        $conn,
        "SELECT 
            TO_CHAR(pal.MovementDate,'YYYYMM') YM, TO_CHAR(pal.MovementDate,'MON') MON
            , TRUNC(pal.movementdate) MovementDate
            , COUNT(CASE WHEN pal.movementtype = 'C-' THEN pal.MovementType END) count_out
            , COUNT(CASE WHEN pal.movementtype = 'C+' THEN pal.MovementType END) count_in
            , COUNT(CASE WHEN pal.movementtype = 'C+' THEN pal.MovementType END) -
            COUNT(CASE WHEN pal.movementtype = 'C-' THEN pal.MovementType END) count_inout
        FROM m_productasset_log pal
        INNER JOIN M_ProductAsset pa ON (pa.M_ProductAsset_ID=pal.M_ProductAsset_ID)
        WHERE pal.movementdate BETWEEN TRUNC(SYSDATE)-30 AND SYSDATE
        AND pa.M_Product_ID LIKE '%$packing_type%'
        GROUP BY TO_CHAR(pal.MovementDate,'YYYYMM'), TO_CHAR(pal.MovementDate,'MON'), TRUNC(pal.movementdate)"
    );

    oci_execute($stid);

    $sum_in = 0;
    $sum_out = 0;
    $sum_inout = 0;
    $count = array(array());

    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
        $count[htmlspecialchars($row['MOVEMENTDATE'], ENT_NOQUOTES, 'UTF-8')]['count_in'] = htmlspecialchars($row['COUNT_IN'], ENT_NOQUOTES, 'UTF-8');
        $count[htmlspecialchars($row['MOVEMENTDATE'], ENT_NOQUOTES, 'UTF-8')]['count_out'] = htmlspecialchars($row['COUNT_OUT'], ENT_NOQUOTES, 'UTF-8');
        $count[htmlspecialchars($row['MOVEMENTDATE'], ENT_NOQUOTES, 'UTF-8')]['count_inout'] = htmlspecialchars($row['COUNT_INOUT'], ENT_NOQUOTES, 'UTF-8');

        $sum_in = $sum_in + $row['COUNT_IN'];
        $sum_out = $sum_out + $row['COUNT_OUT'];
        $sum_inout = $sum_inout + $row['COUNT_INOUT'];
    }
    //print_r($count);
    // END 

    $dateStart = date("Y-m-d", strtotime("-30 day"));
    $dateEnd = date("Y-m-d");
    $months = array('-', 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OKT', 'NOV', 'DES');
?>

    <table id="" class="display table table-bordered" style="width:100%">

        <?php $headings = array(' ', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'); ?>

        <tr class="table-primary" style="font-size:13px">
            <td class="text-center">
                <?= implode('</td><td class="text-center">', $headings); ?>
            </td>
        </tr>

        <?php
        $weekRow;
        $currentDate = $dateStart;
        $currentMonth = intval(date("n", strtotime($currentDate)));
        $thisMonth = 0;

        $x = intval(date("W", strtotime($dateStart)));
        $y = intval(date("W", strtotime($dateEnd)));
        if ($x > $y) {
            $y = $y + getIsoWeeksInYear(date_format(date_create(date('Y-m-d', strtotime('-1 year'))), 'Y'));
        }

        $totalRow = 1 + ($y - $x);

        if (date("n", strtotime($dateStart)) != date("n", strtotime($dateEnd)))
            $totalRow++;

        for ($i = 0; $i < $totalRow; $i++) {
        ?>
            <tr style="font-size:11px">
                <?php
                for ($j = 0; $j <= 7; $j++) {
                    if ($j == 0) {
                        if ($thisMonth != $currentMonth) {
                            $firstMonday = "this week";
                            if (date("n", strtotime($dateStart)) == date("n", strtotime($dateEnd))) {
                                $weekRow = 1 + (intval(date("W", strtotime($dateEnd))) - intval(date("W", strtotime($dateStart))));
                                if (date("N", strtotime($dateStart)) == 7)
                                    $firstMonday = "last monday this week";

                                $currentDate = date("Y-m-d", strtotime($firstMonday, strtotime($dateStart)));
                            } else {
                                $thisMonthStart = date("Y-m-d", strtotime($currentDate));
                                $thisMonthEnd = date("Y-m-t", strtotime($currentDate));
                                $weekRow = 1 + (intval(date("W", strtotime($thisMonthEnd))) - intval(date("W", strtotime($thisMonthStart))));
                                $dateStart = $currentDate;
                                if (date("N", strtotime($dateStart)) == 7)
                                    $firstMonday = "last monday this week";
                                $currentDate = date("Y-m-d", strtotime($firstMonday, strtotime($dateStart)));
                            }
                            $thisMonth = intval(date("n", strtotime($dateStart)));
                            $currentMonth = $thisMonth;
                            if ($weekRow <= 0) {
                                $weekRow = 1 + $y - $x;
                            }
                            echo "<td style='vertical-align:middle;text-align:center;font-size:13px' rowspan='$weekRow'>$months[$currentMonth]";
                        }
                    } else {
                        //j = menampilkan tanggal 1, 2, .., 30 (tanpa angka nol)
                        //$print_date = date('j', strtotime($value)) . "<sup>" . date('M', strtotime($value)) . "</sup>";
                        // N - The ISO-8601 numeric representation of a day (1 for Monday, 7 for Sunday)
                        //$date_val = date('N', strtotime($value));
                        $bg_color = "secondary";
                        $tanggal = strtoupper(date("d-M-y", strtotime($currentDate)));
                        $print_date = date('j', strtotime($currentDate));
                        $thisMonth = intval(date("n", strtotime($currentDate)));
                        if (array_key_exists($tanggal, $count)) {
                            $count_in = $count[$tanggal]['count_in'];
                            $count_out = $count[$tanggal]['count_out'];
                            $count_inout = $count[$tanggal]['count_inout'];

                            //atur warna backgroun cell/kolom
                            if ($count_in < $count_out) {
                                $bg_color = "danger"; //warna merah
                            } else if ($count_in > $count_out) {
                                $bg_color = "success"; //warna hijau
                            } else {
                                $bg_color = "warning"; //warna kuning
                            }
                        } else {
                            $count_in = 0;
                            $count_out = 0;
                            $count_inout = 0;
                        }

                        if ($currentDate < $dateStart) {
                            echo "<td>" . "</td>"; //. $dateStart ."-" . $currentDate 
                            $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
                        } else {

                            if ($thisMonth == $currentMonth) {
                                if ($currentDate <= $dateEnd) {
                                    $date_for_tooltips = date_format(date_create($currentDate), 'd-M-Y');
                                    $dateurl = date_format(date_create($currentDate), 'd-m-Y');
                                    echo "<td class='text-center bg-$bg_color mobile-tooltips' style='font-size:16px' dateurl='$dateurl' datecal='$date_for_tooltips' datain='$count_in' dataout='$count_out' datadiff='$count_inout' title='In : $count_in &#013;Out : $count_out &#013;Diff : $count_inout'>$print_date</td>";
                                    $currentDate = date("Y-m-d", strtotime("+1 day", strtotime($currentDate)));
                                } else {
                                    echo "<td></td>";
                                }
                            } else {
                                echo "<td></td>";
                            }
                        }
                    }
                }

                ?>
            </tr>
        <?php
        } //end For $i

        ?>
    </table>

    <?php
    //Hitung presentase antara jumlah packing in dan packing out
    //Digunakan untuk progressbar dibawah tabel.
    $total_inout = $sum_in + $sum_out;
    $percent_in = 0;
    $percent_out = 0;
    // $percent_total = $percent_in + $percent_out;

    // jika total inout bukan 0
    // maka hitung presentase menggunakan rumus berikut
    if ($total_inout != 0) {
        $percent_in = number_format($sum_in / $total_inout * 100, 0, 0, '.');
        $percent_out = number_format($sum_out / $total_inout * 100, 0, 0, '.');
    }

    ?>

    <div class="row mb-3">
        <div class="col-md-4">
            30-day Cumulative Packing In / Out
            <div class="progress" style="height: 22px;font-size:14px">
                <div class="progress-bar progress-bar-striped bg-success font-weight-bold" role="progressbar" style="width: <?= $percent_in . '%'; ?>" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">
                    <?= $sum_in . " (" . $percent_in . "%)"; ?>
                </div>
                <div class="progress-bar progress-bar-striped bg-danger font-weight-bold" role="progressbar" style="width: <?= $percent_out . '%'; ?>" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                    <?= $sum_out . " (" . $percent_out . "%)"; ?>
                </div>
            </div>

            <!-- <div class="progress mt-1" style="height: 18px;">
                <div class="progress-bar progress-bar-striped progress-bar-sm bg-success font-weight-bold" role="progressbar" style="width:<?= $percent_in . '%'; ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $sum_in . " (" . $percent_in . "%)"; ?></div>
            </div>
            <div class="progress mt-1" style="height: 18px;">
                <div class="progress-bar progress-bar-striped bg-danger font-weight-bold" role="progressbar" style="width:<?= $percent_out . '%'; ?>" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"><?= $sum_out . " (" . $percent_out . "%)"; ?></div>
            </div> -->

        </div>
    </div>

<?php
}

/**
 * fungsi untuk menghitung jumlah minggu dalam 1 tahun tertentu, misal:
 * 2020 = 53 minggu
 * 2021 = 52 minggu
 * 2022 = 52 minggu
 * 
 * pengggunaan fungsi:
 * echo getIsoWeeksInYear(2020)
 * hasil: 53
 */
function getIsoWeeksInYear($year)
{
    $date = new DateTime;
    $date->setISODate($year, 53);
    return ($date->format("W") === "53" ? 53 : 52);
}
