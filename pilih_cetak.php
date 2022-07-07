<div class=" container">
    <h4 class="text-center mt-3">CETAK SURAT JALAN PACKING</h4>
    <hr>

    <div class="row justify-content-center">
        <div class="col-sm-5">
            <form action="index.php" method="GET">
                <input type="hidden" name='page' value="pilih_cetak">
                <input type="text" name="cari" class="form-control mb-3" placeholder="Masukan Surat Jalan" required>
                <p class="text-center">
                    <input type="submit" class="btn btn-success" value="CARI">
                </p>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET['cari']) && !empty($_GET['cari'])) {

        $dn = $_GET['cari'];

        $stid = oci_parse(
            $conn,
            "SELECT MIO.M_INOUT_ID, MIO.DOCUMENTNO, CBP.NAME, TO_CHAR(MPAL.CREATED, 'DD-MON-YYYY') CREATED
            FROM M_PRODUCTASSET_LOG MPAL 
            JOIN M_PRODUCT MP ON MPAL.M_PRODUCT_ID = MP.M_PRODUCT_ID
            JOIN M_INOUT MIO ON MPAL.M_INOUT_ID = MIO.M_INOUT_ID
            JOIN C_BPARTNER CBP ON MIO.C_BPARTNER_ID = CBP.C_BPARTNER_ID
            WHERE MPAL.M_INOUT_ID = (SELECT M_INOUT_ID FROM M_INOUT WHERE DOCUMENTNO = '$dn')
            AND ROWNUM = 1"
        );

        // Perform the logic of the query
        oci_execute($stid);
    ?>

        <h5 class="text-center mt-3">Hasil Pencarian</h5>
        <div class="table-responsive">
            <table id="datatable" class="display table table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Surat Jalan</th>
                        <th scope="col" class="text-center">Tanggal</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    ?>
                        <tr>
                            <td><b><?= $row['DOCUMENTNO'] . "</b><br>" . $row['NAME']; ?></td>
                            <td><?= $row['CREATED']; ?></td>
                            <td style="text-align:center"><a href="<?= $base_url; ?>cetak.php?m_inout_id=<?= $row['M_INOUT_ID']; ?>" target="_blank" class="btn btn-success"><i class="fa fa-file-pdf-o"></i></a>
                        </tr>
                    <?php
                    }
                    ?> </tbody>
            </table>
        </div> <?php
            }
                ?>
</div>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            responsive: true,
            searching: false,
            paging: false,
            infoEmpty: false,
            ordering: false,
            info: false,
            "language": {
                "processing": "Loading...",
                "sEmptyTable": "Data tidak ditemukan.",
            }
        });
    });
</script>