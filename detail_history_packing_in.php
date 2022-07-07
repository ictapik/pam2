<div class="container">

    <?php
    $tnkb = $_GET["tnkb"];
    $movementdate = $_GET["movementdate"];
    ?>

    <table class="mt-3">
        <tr>
            <th>TNKB</th>
            <td>: <?= $tnkb; ?></td>
        </tr>
        <tr>
            <th>DATE</th>
            <td>: <?= date_format(date_create($movementdate), 'd-M-Y'); ?></td>
        </tr>
    </table>
    <hr>

    <table id="datatable" class="display table table-striped" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th style="text-align: center;">NO</th>
                <th>TIPE PACKING</th>
                <th style="text-align: center;">QTY BOX</th>
                <th>SERIAL NO/ID NO</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Prepare the statement
            if ($tnkb == "null") {
                $stid = oci_parse($conn, "SELECT 
                    MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME, MP.NAME NAME_PRODUCT, MP.VALUE, MPAL.M_PRODUCTASSET_LOG_ID 
                    FROM M_PRODUCTASSET_LOG MPAL
                    JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
                    JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
                    WHERE MPAL.MOVEMENTTYPE = 'C+'
                    AND MPAL.TNKB IS NULL
                    AND TO_CHAR(MPAL.MOVEMENTDATE, 'DD-MM-YYYY') = '$movementdate'            
                    ORDER BY MPA.NAME ASC");
            } else {
                $stid = oci_parse($conn, "SELECT 
                    MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME, MP.NAME NAME_PRODUCT, MP.VALUE, MPAL.M_PRODUCTASSET_LOG_ID 
                    FROM M_PRODUCTASSET_LOG MPAL
                    JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
                    JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
                    WHERE MPAL.MOVEMENTTYPE = 'C+'
                    AND MPAL.TNKB = '$tnkb'
                    AND TO_CHAR(MPAL.MOVEMENTDATE, 'DD-MM-YYYY') = '$movementdate'            
                    ORDER BY MPA.NAME ASC");
            }

            oci_execute($stid);

            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                $tags[htmlspecialchars($row['NAME_PRODUCT'], ENT_NOQUOTES, 'UTF-8')][] = htmlspecialchars($row['NAME'], ENT_NOQUOTES, 'UTF-8');
            }

            $no = 1;
            $jumlah = 0;

            foreach ($tags as $key => $value) {
            ?>
                <tr>
                    <td style='text-align:center'><?= $no ?></td>
                    <td><?= $key ?></td>
                    <td style='text-align:center'><?= count($tags[$key]); ?></td>
                    <td><?= implode(", ", $tags[$key]); ?></td>
                </tr>
            <?php
                $jumlah = $jumlah + count($tags[$key]);
                $no = $no + 1;
            }
            ?>

            <tr>
                <th colspan='2' style='text-align:center'>JUMLAH</th>
                <th style='text-align:center'><?= $jumlah ?></th>
                <th></th>
            </tr>

        </tbody>
    </table>

    <p class="text-center mt-3">
        <a href='index.php?page=history_packing_in' class='btn btn-secondary'>KEMBALI</a>
    </p>

</div>