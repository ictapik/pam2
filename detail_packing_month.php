<?php
$month = $_GET['month'];
$month_txt = date_format(date_create($month), 'M Y');
?>

<div class="container">
    <h4 class="text-center mt-3 font-weight-bold">PACKING <?= strtoupper($month_txt); ?></h4>
    <hr>

    <table id="datatable" class="display table table-striped table-bordered responsive nowrap" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th class="text-center" data-priority="1">TANGGAL</th>
                <th class="text-center">IN</th>
                <th class="text-center">OUT</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $stid = oci_parse(
                $conn,
                "SELECT 
                TRUNC(pal.movementdate) AS movementdate,
                                        SUM(CASE WHEN pal.movementtype = 'C+' THEN 1 ELSE 0 END) count_in,
                                        SUM(CASE WHEN pal.movementtype = 'C-' THEN 1 ELSE 0 END) count_out
                                    FROM m_productasset_log pal
                                    WHERE TO_CHAR(pal.movementdate, 'YYYY-MM') = '$month'
                                    GROUP BY TRUNC(pal.movementdate)"
            );
            oci_execute($stid);


            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) { ?>
                <tr>
                    <td class="text-center"><?= $row['MOVEMENTDATE']; ?></td>
                    <td class="text-center"><?= $row['COUNT_IN']; ?></td>
                    <td class="text-center"><?= $row['COUNT_OUT']; ?></td>
                </tr><?php
                    }
                        ?>
        </tbody>

    </table>
</div>

<script>
    $(document).ready(function() {

        $('#datatable').DataTable({
            // responsive: true,
            "pageLength": 50,
            "infoEmpty": false,
            "language": {
                "processing": "Loading...",
                // "sEmptyTable": "Data tidak ditemukan.",
            },
            "columnDefs": [{
                    "targets": [0, 1], //last column
                    "orderable": false, //set not orderable
                }, {
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: -1
                }
            ],
        });
    });
</script>