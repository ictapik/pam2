<div class="container">

    <?php
    $m_inout_id = $_GET['m_inout_id'];

    $stid = oci_parse($conn, "SELECT documentno, name, NVL(tnkb, '-') AS tnkb, TO_CHAR(movementdate, 'DD MON YYYY') AS movementdate
    From M_inOut mio
    JOIN C_BPartner cbp ON mio.C_BPartner_ID = cbp.C_BPartner_ID
    WHERE M_InOut_ID = $m_inout_id");

    // Perform the logic of the query
    oci_execute($stid);
    $row_header = oci_fetch_array($stid, OCI_ASSOC);
    ?>

    <table class="mt-3">
        <tr>
            <th>DN NO</th>
            <td width="150px">: <?= $row_header['DOCUMENTNO']; ?></td>
            <th>SHIP TO</th>
            <td>: <?= $row_header['NAME']; ?></td>
        </tr>
        <tr>
            <th>DATE</th>
            <td>: <?= $row_header['MOVEMENTDATE']; ?></td>
            <th>TNKB</th>
            <td>: <?= $row_header['TNKB']; ?></td>
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


        <?php
        // Prepare the statement
        $stid = oci_parse($conn, "SELECT 
            MPAL.M_INOUT_ID, MPAL.SERNO, MPA.NAME, MP.NAME NAME_PRODUCT, MP.VALUE, MPAL.M_PRODUCTASSET_LOG_ID 
            FROM M_PRODUCTASSET_LOG MPAL
            JOIN M_PRODUCTASSET MPA ON MPAL.M_PRODUCTASSET_ID = MPA.M_PRODUCTASSET_ID
            JOIN M_PRODUCT MP ON MPA.M_PRODUCT_ID = MP.M_PRODUCT_ID
            WHERE MPAL.M_INOUT_ID = '$m_inout_id'
            ORDER BY MPA.NAME ASC");

        oci_execute($stid);

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

    <!-- <table id="datatable" class="display table table-striped" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th>NAME</th>
                <th>SERNO</th>
                <th class="text-center">MOVEMENT TYPE</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table> -->

    <p class="text-center mt-3">
        <a href='index.php?page=history_packing_out' class='btn btn-secondary'>KEMBALI</a>
    </p>

</div>