<div class=" container">
    <h4 class="text-center font-weight-bold mt-3">PACKING OUT</h4>
    <h4 class="text-center">PILIH TRUK</h4>
    <hr>

    <div class="row justify-content-center">
        <div class="col-sm-5">
            <form action="index.php" method="GET">
                <input type="hidden" name="page" value="surat_jalan">
                <select name="tnkb" class="form-control mb-3" required>
                    <option></option>

                    <?php
                    // Prepare the statement
                    $stid = oci_parse($conn, "SELECT VALUE, NAME FROM AD_Ref_List WHERE (VALIDTO IS NULL OR 
                    
                    ValidTo >= SYSDATE) AND AD_Reference_ID=1000049");
                    if (!$stid) {
                        $e = oci_error($conn);
                        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                    }

                    // Perform the logic of the query
                    $r = oci_execute($stid);
                    if (!$r) {
                        $e = oci_error($stid);
                        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
                    }

                    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    ?>
                        <option value="<?= $row['VALUE']; ?>"><?= $row['NAME']; ?></option>
                    <?php
                    }

                    oci_free_statement($stid);
                    ?>

                </select>
                <p class="text-center">
                    <input type="submit" class="btn btn-success" value="PROSES">
                </p>
            </form>
        </div>
    </div>

    <h5 class="text-center mt-3">PENDING LOAD</h5>
    <div class="">
        <table id="datatable" class="display table table-striped" style="width:100%">
            <thead>
                <tr class="table-primary">
                    <th scope="col" class="text-center">SURAT JALAN</th>
                    <th scope="col" class="text-center">TNKB</th>
                    <th scope="col" class="text-center">QTY</th>
                    <th scope="col" class="text-center">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Prepare the statement
                $stid = oci_parse(
                    $conn,
                    "SELECT 
                        mpal.M_INOUT_ID, mio.DocumentNo, TO_CHAR(mpal.MOVEMENTDATE, 'YYYY-MM-DD') MOVEMENTDATE, mpal.TNKB, COUNT(*) AS QTY 
                    FROM M_PRODUCTASSET_LOAD mpal
                    JOIN M_InOut mio ON mpal.M_inOut_ID = mio.M_inOut_ID
                    WHERE mpal.MOVEMENTTYPE = 'C-' 
                    GROUP BY mpal.M_INOUT_ID, mio.DocumentNo, mpal.MOVEMENTDATE, mpal.TNKB"
                );

                // Perform the logic of the query
                oci_execute($stid);

                while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                ?>
                    <tr>
                        <td class="text-center"><?= $row['DOCUMENTNO']; ?></td>
                        <td class="text-center"><?= $row['TNKB']; ?></td>
                        <td class="text-center"><?= $row['QTY']; ?></td>
                        <td class="text-center">
                            <form action="index.php" method="GET">
                                <input type="hidden" name="page" value="scan" readonly>
                                <input type="hidden" name="tnkb" value="<?= $row['TNKB']; ?>" class="form-control" readonly>
                                <input type="hidden" name="m_inout_id" value="<?= $row['M_INOUT_ID']; ?>" class="form-control" readonly>
                                <input type="hidden" name="movementdate" value="<?= $row['MOVEMENTDATE']; ?>" class="form-control" readonly>
                                <button type="submit" class="btn btn-success"><i class="fa fa-truck"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php
                }

                oci_free_statement($stid);
                oci_close($conn);
                ?>
            </tbody>
        </table>
    </div>

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