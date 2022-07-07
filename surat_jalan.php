<div class="container">
    <h4 class="text-center font-weight-bold mt-3">PILIH SURAT JALAN</h4>

    <?php
    $tnkb = $_GET['tnkb'];
    $stid = oci_parse($conn, "SELECT NAME FROM AD_REF_LIST WHERE VALUE = '$tnkb'");
    oci_execute($stid);
    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
    ?>
        <h6 class="text-center mb-3"><?= strtoupper($row['NAME']); ?></h6>
    <?php
    }
    ?>
    <hr>

    <div class="row">

        <div class="col-sm-8">
            <table id="datatable" class="display table table-striped" style="width:100%">
                <thead>
                    <tr class="table-primary">
                        <th scope="col">SURAT JALAN</th>
                        <th scope="col">LOADING</th>
                        <th scope="col">TANGGAL</th>
                        <th scope="col" class="text-center">ACTION</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $tnbk = $_GET['tnkb'];

                    $stid = oci_parse(
                        $conn,
                        "SELECT io.M_InOut_ID, io.DocumentNo, io.POReference, 
                        TO_CHAR(io.MovementDate, 'DD-MON-YYYY') MovementDate, 
                        TO_CHAR(io.MovementDate, 'YYYY-MM-DD') Mvmnt, NVL(TO_CHAR(io.PlanTIme,'hh24:mi'),'--:--') PlanTime, 
                        bp.Name,
                        (SELECT COUNT(SERNO) FROM M_ProductAsset_load mal WHERE io.M_InOut_ID=mal.M_InOut_ID AND mal.TNKB='$tnbk') Load,
                        (SELECT ROUND(SUM(iol.MovementQty/p.UnitsPerPack),0) FROM M_InOutLine iol INNER JOIN M_Product p ON (iol.M_Product_ID=p.M_Product_ID) WHERE iol.M_InOut_ID=io.M_InOut_ID) LoadX
                        FROM M_InOut io 
                        INNER JOIN C_BPartner bp ON (io.C_BPartner_ID=bp.C_BPartner_ID) 
                        WHERE io.TNKB='$tnbk' AND io.DocStatus='DR' AND
                       NOT EXISTS (SELECT 1 FROM M_ProductAsset_log WHERE M_InOut_ID = io.M_InOut_ID)
                        ORDER BY io.DocumentNo"
                    );

                    // Perform the logic of the query
                    oci_execute($stid);

                    while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                    ?>
                        <tr data-documentno="<?= $row['DOCUMENTNO']; ?>">
                            <td>
                                <b><?= $row['DOCUMENTNO'] . "</b><br>" . substr($row['NAME'], 0, 18); ?>...
                            </td>
                            <td><b><?= $row['LOAD'] . "/" . $row['LOADX']; ?></b></td>
                            <td><?= $row['MOVEMENTDATE'] . " " . $row['PLANTIME']; ?></td>
                            <td class="text-center">
                                <form action="index.php" method="GET">
                                    <input type="hidden" name="page" value="scan" readonly>
                                    <input type="hidden" name="tnkb" value="<?= $_GET['tnkb']; ?>" class="form-control" readonly>
                                    <input type="hidden" name="m_inout_id" value="<?= $row['M_INOUT_ID']; ?>" class="form-control" readonly>
                                    <input type="hidden" name="movementdate" value="<?= $row['MVMNT']; ?>" class="form-control" readonly>

                                    <!-- -->
                                    <div class="dropdown"><button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button>
                                        <div class="dropdown-menu dropdown-menu-right">

                                            <button class="dropdown-item" type="submit"><i class="fa fa-truck mr-2"></i>Loading</button>

                                            <button type="button" class="dropdown-item assign-here" value="<?= $row['M_INOUT_ID']; ?>"><i class="fa fa-download mr-2"></i> Insert</button>

                                            <button type="button" data-m_inout_id="<?= $row['M_INOUT_ID']; ?>" class="dropdown-item show_detail"><i class="fa fa-eye mr-2"></i>Detail</button>

                                            <button type="button" data-finish="<?= $row['M_INOUT_ID']; ?>" data-movementdate="<?= $row['MVMNT']; ?>" class="dropdown-item finish"><i class="fas fa-flag-checkered mr-2"></i> Finish</button>

                                        </div>
                                    </div>
                                    <!-- -->

                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <p class="text-center mt-3">
                <a href='index.php?page=tnkb' class='btn btn-secondary'>KEMBALI</a>
            </p>
        </div>

        <div class="col-sm-4">
            <table id="datatable-rfid-inventory" class="display table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr class="table-primary">
                        <th scope="col">
                        </th>
                        <th scope="col">NAME</th>
                        <th scope="col">DATE</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

</div>

<!-- MODAL DETAIL PART -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold" id="exampleModalLabel">DETAIL PART</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="display table table-bordered">
                    <tr>
                        <th>PACKING TYPE</th>
                        <th>PART NO</th>
                        <th>PART NAME</th>
                        <th>UNITS PER PACK</th>
                        <th>QTY PART</th>
                        <th>QTY PACKING</th>
                    </tr>
                    <tr>
                        <td id="packingtype"></td>
                        <td id="partno"></td>
                        <td id="partname"></td>
                        <td id="unitsperpack"></td>
                        <td id="qtypart"></td>
                        <td id="qtypacking"></td>
                    </tr>
                    <tr>
                        <td id="detail_asset" colspan="6">
                        </td>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
<!-- END MODAL DETAIL PART -->

<!--MODAL FINISH-->
<div class="modal fade" id="ModalFinish" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="myModalLabel">FINISH LOADING</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
            </div>
            <form method="POST" class="form-horizontal">
                <div class="modal-body">

                    <input type="hidden" name="finish_id" id="finish_id" value="" readonly>
                    <input type="hidden" name="finish_movementdate" id="finish_movementdate" value="" readonly>

                    <!-- <div class="alert alert-danger"> -->
                    <h6>Yakin akan finish loading?</h6>
                    <!-- </div> -->

                </div>
                <div class="modal-footer">
                    <button type="button" id="konfirm_finish" class="btn btn-success">YA, FINISH!</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">TIDAK</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--END MODAL FINISH-->

<script>
    $(document).ready(function() {

        let cb_serno = new Array();
        let tnkb = "<?= $_GET['tnkb']; ?>";
        let documentno = "";
        let s_cb_serno = "";

        var table = $('#datatable').DataTable({
            responsive: true,
            searching: false,
            paging: false,
            infoEmpty: false,
            ordering: false,
            info: false,
            sScrollY: "350",
            "language": {
                "processing": "Loading...",
                "sEmptyTable": "Data tidak ditemukan.",
            }
        });

        $('#datatable tbody').on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                documentno = "";
                console.log("unselect");
                table_inventory.ajax.reload();
            } else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                console.log($(this).data('documentno'));
                documentno = $(this).data('documentno');
                console.log("select");
                table_inventory.ajax.reload();
            }
        });

        $('.show_detail').on('click', function() {
            var m_inout_id = $(this).data('m_inout_id');

            $.ajax({
                type: "POST",
                url: "ajax_detail_part.php",
                dataType: "JSON",
                data: {
                    m_inout_id: m_inout_id,
                },
                success: function(data) {
                    $('#packingtype').text(data.PACKINGTYPE);
                    $('#partno').text(data.PARTNO);
                    $('#partname').text(data.PARTNAME);
                    $('#unitsperpack').text(data.UNITSPERPACK);
                    $('#qtypart').text(data.QTYPART);
                    $('#qtypacking').text(data.QTYPACKING);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert("ERROE");
                    console.log(jqXHR.status);
                }
            });

            $.ajax({
                type: "POST",
                url: "ajax_detail_asset.php",
                dataType: "JSON",
                data: {
                    m_inout_id: m_inout_id,
                },
                success: function(data) {
                    console.log(data);

                    var asset_name = "";
                    for (i = 0; i < data.length; i++) {
                        if (i == (data.length - 1)) {
                            asset_name += data[i];
                        } else {
                            asset_name += data[i] + " ";
                        }
                    }
                    show_asset_name = asset_name.split(" ").join(", ");
                    $('#detail_asset').text(show_asset_name);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert("ERROE");
                    console.log(jqXHR.status);
                }
            });

            $('#exampleModal').modal('show');

        });

        var table_inventory = $('#datatable-rfid-inventory').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            // "paging": false,
            "infoEmpty": false,
            "ordering": false,
            "info": false,
            "pageLength": 100,
            "sScrollY": "350",
            "ajax": {
                "url": "ajax_rfid_inventory.php",
                "type": "GET",
                "data": function(d) {
                    d.tnkb = tnkb;
                    d.documentno = documentno;
                }
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }],
            "order": [
                [1, "DESC"]
            ],
            "columns": [{
                    "data": "name",
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<input type="checkbox" name="serno[]" class="cb_serno" value="' + row.serno + '">';
                    }
                },
                {
                    "data": "name",
                    className: "",
                    // render: function(data, type, row) {
                    //     return row.name;
                    // }
                },
                {
                    "data": "created",
                    className: "text-center",
                },
            ]
        });

        $("#select-all").click(function() {
            // $("#pilihan").prop('checked', $(this).prop('checked'));
            // $('input:checkbox').prop('checked', 'checked');
            $('input:checkbox').prop('checked', $(this).prop('checked'));

            console.log($(this).val());
        });

        $(document).on("click", ".cb_serno", function() {

            var id = $(this).val();
            var index = $.inArray(id, cb_serno);

            if (index === -1) {
                cb_serno.push(id);
            } else {
                cb_serno.splice(index, 1);
            }

            s_cb_serno = cb_serno.map(x => "'" + x + "'").toString();

            // console.log($(this).val());
            console.log(cb_serno);
            // var sting_cb_serno = cb_serno.toString();
            // console.log(sting_cb_serno);
            // console.log(cb_serno.toString());
            console.log(s_cb_serno);

        });

        $(".assign-here").click(function() {
            let tnkb, m_inout_id;

            tnkb = $('[name="tnkb"]').val();
            m_inout_id = $(this).val();

            $.ajax({
                type: 'POST',
                url: 'assign_to.php',
                data: {
                    tnkb: tnkb,
                    m_inout_id: m_inout_id,
                    cb_serno: cb_serno,
                    s_cb_serno: s_cb_serno
                },
                async: true,
                dataType: 'json',
                success: function(data) {

                    console.log(data);
                    location.reload();

                    // tampil_scan();
                    // console.log('auto scan is running...');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR.responseText);
                }
            });
        });

        // validsi ketika tombol load dikoik
        // jika ada data di table scan serno makan muncul alert
        // jika tidak ada data di tabel scan serno langsung proses
        $(".load").click(function() {
            var count = document.getElementById("datatable-rfid-inventory").getElementsByTagName("td").length;
            console.log(count);

            if (count >= 2) {
                return confirm('Masukan semua serno ke surat jalan ini?\nUntuk pilih partial gunakan tombol download.');
            }
        });

        $(".finish").on("click", function() {
            var finish_id = $(this).data('finish');
            var movementdate = $(this).data('movementdate');

            // console.log("KLIK : " + finish_id);

            $('#ModalFinish').modal('show');
            $('[name="finish_id"]').val(finish_id);
            $('[name="finish_movementdate"]').val(movementdate);
        });

        // Proses finish loading
        $('#konfirm_finish').click(function() {
            var finish_id = $('#finish_id').val();
            var finish_movementdate = $('#finish_movementdate').val();

            $.ajax({
                type: 'POST',
                url: "finish_scan.php",
                data: {
                    finish_id: finish_id,
                    finish_movemntdate: finish_movementdate
                },
                success: function() {
                    window.open('./cetak.php?m_inout_id=' + finish_id, '_blank');
                    $("#ModalFinish").modal('hide');
                    // tampil_scan();
                    // Server.send('message', 'reload');
                    // Server.send('message', 'update_dashboard');
                    // window.location.href = "index.php";
                    location.reload();
                }
            });
        });
    });
</script>