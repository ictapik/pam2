<?php
$movementtype = urlencode($_GET['movementtype']);
$date = $_GET['date'];

$inout = $movementtype == "C+" ? "IN" : "OUT";
$inout_date = strtoupper(date_format(date_create($date), 'd-M-Y'));

if ($inout_date == strtoupper(date('d-M-Y')))
    $inout_date = "TODAY";
?>

<div class="container">
    <h4 class="text-center mt-3 font-weight-bold">PACKING <?= $inout . " " . $inout_date; ?></h4>
    <hr>

    <table id="datatable" class="display table table-striped table-bordered responsive nowrap" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th class="text-center" data-priority="1">NAME</th>
                <th class="text-center">SERNO</th>
                <th class="text-center">GATE</th>
                <th class="text-center">TNKB</th>
                <th class="text-center" data-priority="2">TIME</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stid = oci_parse(
                $conn,
                "SELECT b.name, a.serno, NVL(a.tnkb, '-') tnkb, NVL(a.devicename, 'MANUAL') devicename, TO_CHAR(a.created, 'DD-MON-YYYY HH24:MI') created FROM M_ProductAsset_Log a
                JOIN M_ProductAsset b ON b.serno = a.serno
                WHERE a.movementtype = '$movementtype'
                AND TO_CHAR(a.movementdate, 'DD-MM-YYYY') = '$date'"
            );
            oci_execute($stid);
            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            ?>
                <tr>
                    <td><?= $row['NAME']; ?></td>
                    <td><?php echo "<a href='index.php?page=log_assets&status=All&packingid=-&serno=" . $row['SERNO'] . "&name=" . $row['NAME'] . "'>" . $row['SERNO'] . "</a>"; ?></td>
                    <td><?= $row['DEVICENAME']; ?></td>
                    <td><?= $row['TNKB']; ?></td>
                    <td class="text-center"><?= $row['CREATED']; ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {

        $('#datatable').DataTable({
            // responsive: true,
            infoEmpty: false,
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