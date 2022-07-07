<div class=" container">
    <h4 class="text-center mt-3 font-weight-bold">ASSETS</h4>
    <hr>

    <div class="table-responsive">
        <table id="datatable" class="display table table-striped" style="width:100%">
            <thead>
                <tr class="table-primary">
                    <th scope="col" class="text-center" data-priority="1">ID</th>
                    <th scope="col" class="text-center">NAME</th>
                    <th scope="col" class="text-center">QTY IN</th>
                    <th scope="col" class="text-center">QTY OUT</th>
                    <th scope="col" class="text-center" data-priority="1">TOTAL</th>
                    <th scope="col" class="text-center">TAGGED</th>
                    <th scope="col" class="text-center">LAST IN</th>
                    <th scope="col" class="text-center">LAST OUT</th>
                </tr>
            </thead>
            <tbody>

                <?php
                // Prepare the statement
                $stid = oci_parse(
                    $conn,
                    "SELECT p.M_Product_ID productID, p.Value PackingID, p.Name PackingName, p.Prefix, p.Suffix, 
                    COUNT(CASE WHEN MOVEMENTTYPE='C+' THEN pa.Name END) QtyIN,
                    COUNT(CASE WHEN MOVEMENTTYPE='C-' THEN pa.Name END) QtyOUT,
                    COUNT(pa.Name) QtyTOTAL,
                    COUNT(CASE WHEN pa.serno<>pa.name THEN pa.Name End) tagged,
                    COUNT(CASE WHEN pa.serno=pa.name THEN pa.Name End) not_tagged,
                    NVL (TO_CHAR(MIN(log.LASTIN),'DD-MON-YYYY'),'-') LASTIN,
                    NVL (TO_CHAR(MAX(log.LASTOUT),'DD-MON-YYYY'),'-') LASTOUT
                    FROM m_productasset pa
                    INNER JOIN M_Product p ON (pa.M_Product_ID=p.M_Product_ID)
                    LEFT OUTER JOIN (SELECT M_ProductAsset_ID
                                    , MAX(CASE WHEN MOVEMENTTYPE='C+' THEN MovementDate END) LastIN
                                    , MIN(CASE WHEN MOVEMENTTYPE='C-' THEN MovementDate END) LastOUT
                                FROM m_productasset_log              
                                GROUP BY M_ProductAsset_ID) log ON (log.m_productasset_id=pa.m_productasset_id)
                    GROUP BY p.M_Product_ID, p.Value, p.Name, p.Prefix, p.Suffix"
                );

                // Perform the logic of the query
                oci_execute($stid);

                while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    $tagged = $row['TAGGED'];
                    $not_tagged = $row['NOT_TAGGED'];
                    $count_tagged = $tagged + $not_tagged;
                    $percentage = ($tagged / $count_tagged) * 100;

                ?>
                    <form action="" method="post">
                        <tr>
                            <td><?= $row['PACKINGID']; ?></td>
                            <td><?= $row['PACKINGNAME']; ?></td>
                            <td class="text-center">
                                <a href="./index.php?page=detail_assets&status=in&packingid=<?= $row['PACKINGID'] ?>&productid=<?= $row['PRODUCTID']; ?>&prefix=<?= $row['PREFIX']; ?>&suffix=<?= $row['SUFFIX']; ?>"><?= $row['QTYIN']; ?></a>
                            </td>
                            <td class="text-center">
                                <a href="./index.php?page=detail_assets&status=out&packingid=<?= $row['PACKINGID'] ?>&productid=<?= $row['PRODUCTID']; ?>&prefix=<?= $row['PREFIX']; ?>&suffix=<?= $row['SUFFIX']; ?>"><?= $row['QTYOUT']; ?></a>
                            </td>
                            <td class="text-center">
                                <a href="./index.php?page=detail_assets&status=all&packingid=<?= $row['PACKINGID'] ?>&productid=<?= $row['PRODUCTID']; ?>&prefix=<?= $row['PREFIX']; ?>&suffix=<?= $row['SUFFIX']; ?>"><?= $row['QTYTOTAL']; ?></a>
                            </td>
                            <td class="text-center"><?= number_format($percentage, 0, ',', '.'); ?>%</td>
                            <td class="text-center"><?= $row['LASTIN']; ?></td>
                            <td class="text-center"><?= $row['LASTOUT']; ?></td>
                        </tr>
                    </form>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Job Order Info Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <!-- <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="p_id" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Part No</label>
                            <div class="col-md-9">
                                <input name="partno" placeholder="partno" class="form-control" type="text" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Part Name</label>
                            <div class="col-md-9">
                                <input name="partname" placeholder="Product Name" class="form-control" type="text" disabled>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Product Category</label>
                            <div class="col-md-9">
                                <input name="partcategory" placeholder="Product Category" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Packing [Unit/Pack]</label>
                            <div class="col-md-9">
                                <input name="packinfo" placeholder="Packing Type" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_fg" class="form-group">
                            <label class="control-label col-md-3">QOH FG</label>
                            <div class="col-md-9">
                                <input name="qoh_fg" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_delivery" class="form-group">
                            <label class="control-label col-md-3">QOH Delivery</label>
                            <div class="col-md-9">
                                <input name="qoh_delivery" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_oqc" class="form-group">
                            <label class="control-label col-md-3">QOH OQC</label>
                            <div class="col-md-9">
                                <input name="qoh_oqc" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_oha" class="form-group">
                            <label class="control-label col-md-3">QOH On Hold Area</label>
                            <div class="col-md-9">
                                <input name="qoh_oha" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_wip" class="form-group">
                            <label class="control-label col-md-3">QOH WIP</label>
                            <div class="col-md-9">
                                <input name="qoh_wip" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_rm" class="form-group">
                            <label class="control-label col-md-3">QOH Raw Material</label>
                            <div class="col-md-9">
                                <input name="qoh_rm" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                        <div id="qoh_produksi" class="form-group">
                            <label class="control-label col-md-3">QOH Produksi</label>
                            <div class="col-md-9">
                                <input name="qoh_produksi" placeholder="Quantity On Hand" class="form-control" type="text" disabled>
                                <span class="help-block has-error" id="errors"></span>
                            </div>
                        </div>
                    </div>
                </form> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            responsive: true,
            // searching: false,
            // paging: false,
            infoEmpty: false,
            // ordering: false,
            // info: false,
            // sScrollY: "350",
            "language": {
                "processing": "Loading...",
                "sEmptyTable": "Data tidak ditemukan.",
            }
        });
    });
</script>