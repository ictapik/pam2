<style>
    .table-bordered>tbody>tr>th,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>thead>tr>th {
        /* border: 1px solid #71d5e2; */
        padding: 8px;
    }

    .bg-secondary {
        background-color: #9ba0a5 !important;
    }
</style>

<div class="container">

    <div class="row mt-3">
        <div class="col-md-12">

            <?php
            $stid = oci_parse(
                $conn,
                "SELECT 
                    m_product_id, value, name
                FROM M_Product
                WHERE M_Product_ID IN (SELECT M_Product_ID FROM M_ProductAsset)"
            );
            oci_execute($stid);
            ?>

            <form>
                <input type="hidden" name="page" value="dashboard" readonly>

                <div class="form-row align-items-center">
                    <div class="col-sm-4 my-1">
                        <label class="sr-only" for="inlineFormInputName"></label>
                        <select name="packing_type" class="form-control form-control-sm" id="inlineFormInputName">
                            <option value="">-- All --</option>
                            <?php
                            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
                            ?>
                                <option value="<?= $row['M_PRODUCT_ID']; ?>" <?= (isset($_GET['packing_type']) && !empty($_GET['packing_type']) && $_GET['packing_type'] == $row['M_PRODUCT_ID']) ? "selected" : "" ?>>
                                    <?= "[" . $row['VALUE'] . "] " . $row['NAME']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-sm btn-primary">SUBMIT</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="row mt-3">

        <?php
        $packing_type = "";
        if (isset($_GET['packing_type'])) {
            $packing_type = $_GET['packing_type'];
        }
        ?>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">

                    <?php
                    $stid = oci_parse(
                        $conn,
                        "SELECT COUNT(serno) FROM m_productasset_log
                                WHERE movementtype = 'C+'
                                AND TRUNC(movementdate)  = TRUNC(sysdate)
                                AND m_product_id LIKE '%$packing_type%'"
                    );
                    oci_execute($stid);
                    while (($packing_in = oci_fetch_row($stid)) != false) {
                        echo "<h3><a href='index.php?page=detail_packing&movementtype=C+&date=" . date('d-m-Y') . "' style='color:#ffffff'>" . $packing_in[0] . "</a></h3>";
                    }
                    ?>


                    <p>Packing In Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-box-open"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">

                    <?php
                    $stid = oci_parse(
                        $conn,
                        "SELECT COUNT(serno) FROM m_productasset_log
                                WHERE movementtype = 'C-'
                                AND TRUNC(movementdate)  = TRUNC(sysdate)
                                AND m_product_id LIKE '%$packing_type%'"
                    );
                    oci_execute($stid);
                    while (($packing_out = oci_fetch_row($stid)) != false) {
                        echo "<h3><a href='index.php?page=detail_packing&movementtype=C-&date=" . date('d-m-Y') . "' style='color:#ffffff'>" . $packing_out[0] . "</a></h3>";
                    }
                    ?>

                    <p>Packing Out Today</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shipping-fast"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">

                    <?php
                    $stid = oci_parse(
                        $conn,
                        "SELECT NVL(COUNT(CASE WHEN ri.DEVICENAME = 'RFID 1' THEN ri.Serno END),0) gate_in,
						        NVL(COUNT(CASE WHEN ri.DEVICENAME = 'RFID 2' THEN ri.Serno END),0) gate_out
                         FROM rfid_inventory ri
						   INNER JOIN m_productasset pa ON (ri.serno=pa.serno AND pa.IsActive='Y')"
                    );
                    oci_execute($stid);
                    while (($scaned = oci_fetch_row($stid)) != false) {
                        echo "<h3><a href='index.php?page=detail_pending&gate=RFID 1' style='color:#ffffff'>" . $scaned[0] . "</a> / <a href='index.php?page=detail_pending&gate=RFID 2' style='color:#ffffff'>" . $scaned[1] . "</a></h3>";
                    }
                    ?>

                    <p>Pending @ GATE 1 | GATE 2</p>
                </div>
                <div class="icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-info">
                <div class="inner">

                    <?php
                    $stid = oci_parse(
                        $conn,
                        "SELECT NVL(COUNT(CASE WHEN pa.MovementType = 'C-' THEN ri.Serno END),0) gate_in,
						        NVL(COUNT(CASE WHEN pa.MovementType = 'C+' THEN ri.Serno END),0) gate_out
                         FROM rfid_inventory ri
						   INNER JOIN m_productasset pa ON (ri.serno=pa.serno AND pa.IsActive='Y')"
                    );
                    oci_execute($stid);
                    while (($scaned = oci_fetch_row($stid)) != false) {
                        echo "<h3><a href='index.php?page=detail_pending_in' style='color:#ffffff'>" . $scaned[0] . "</a> / <a href='index.php?page=detail_pending_out' style='color:#ffffff'>" . $scaned[1] . "</a></h3>";
                    }
                    ?>

                    <p>Pending Packing IN / OUT</p>
                </div>
                <div class="icon">
                    <i class="fas fa-qrcode"></i>
                </div>
                <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">

            <?php
            $packing_type = null;
            if (isset($_GET['packing_type'])) {
                $packing_type = $_GET['packing_type'];
            }

            $date = date('Y-m-d');

            //jika peralihan antara Desember tahun sebelumnya ke Januari tahun berikutnya,
            //maka gunakan fungsi yang berbeda untuk menangani/menampilkan kalender packing
            $dateStart = date("Y-m-d", strtotime("-30 day"));
            $dateEnd = date("Y-m-d");
            $x = intval(date("W", strtotime($dateStart)));
            $y = intval(date("W", strtotime($dateEnd)));
            if ($x > $y) {
                //peralihan tahun dari Desember ke Januari
                echo dashboard_cal_2(date('Y-m-d', strtotime($date . "-30 days")), $date, $packing_type);
            } else {
                //tahun normal
                echo dashboard_cal(date('Y-m-d', strtotime($date . "-30 days")), $date, $packing_type);
            }

            ?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="" class="display table table-bordered" style="width:100%">
                <tr>
                    <?php echo dashboard_month(); ?>
                </tr>
            </table>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        let auto_scan = true;
        let count_auto_scan = 1;

        //Define websocket server
        var Server;
        Server = new FancyWebSocket('ws://192.168.3.7:9300');
        Server.bind('message', function(payload) {
            switch (payload) {
                case 'update_dashboard':
                    location.reload();
                    console.log('Updated dasboard by Websocket!');
                    break;
            }
        });
        Server.connect();

        $('.mobile-tooltips').on('click', function() {

            let date_cal = $(this).attr("datecal");
            let data_in = $(this).attr("datain");
            let data_out = $(this).attr("dataout");
            let data_diff = $(this).attr("datadiff");

            if (data_in != "0" || data_out != "0") {
                let date_url = $(this).attr("dateurl")
                if (data_in != 0)
                    data_in = "<a href='index.php?page=detail_packing&movementtype=C+&date=" + date_url + "'>" + data_in + "</a>";

                if (data_out != 0)
                    data_out = "<a href='index.php?page=detail_packing&movementtype=C-&date=" + date_url + "'>" + data_out + "</a>";

                Swal.fire({
                    html: "<font style='font-size:20px'>" + date_cal + "<hr> In: " + data_in + "<br>" + "Out: " + data_out + "<br>" + "Diff: " + data_diff + "</font>",
                    width: 300,
                });
            }
        });

        $('.month-tooltips').on('click', function() {

            let date_cal = $(this).attr("datecal");
            let data_in = $(this).attr("datain");
            let data_out = $(this).attr("dataout");
            let data_diff = $(this).attr("datadiff");

            if (data_in != "0" || data_out != "0") {
                let date_url = $(this).attr("dateurl")
                // if (data_in != 0)
                //     data_in = "<a href='index.php?page=detail_packing_month&month=" + date_url + "' target='_blank'>" + data_in + "</a>";

                // if (data_out != 0)
                //     data_out = "<a href='index.php?page=detail_packing_month&month=" + date_url + "' target='_blank'>" + data_out + "</a>";

                Swal.fire({
                    html: "<font style='font-size:20px'><a href='index.php?page=detail_packing_month&month=" + date_url + "' target='_blank'>" + date_cal + "</a><hr> In: " + data_in + "<br>" + "Out: " + data_out + "<br>" + "Diff: " + data_diff + "</font>",
                    width: 300,
                });
            }
        });
    });
</script>