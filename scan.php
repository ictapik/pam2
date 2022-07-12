<style>
  .buttons {
    top: 0px;
    right: 0px;
  }

  h4 {
    right: 50%;
  }
</style>

<div class="container">

  <div class="text-center mt-3 position-relative">
    <h4 class="w-100 text-center font-weight-bold">PACKING OUT</h4>

    <?php
    $stid = oci_parse(
      $conn,
      "SELECT * FROM RFID_PARAMETER WHERE parameter = 'auto_scan_out'"
    );
    oci_execute($stid);
    $row_parameter = oci_fetch_array($stid, OCI_ASSOC);
    $auto_scan_out = $row_parameter['VALUE'];
    ?>

    <div class="position-absolute buttons">
      <span class='switch custom-control custom-switch'>
        <input type='checkbox' data-value='' class='custom-control-input' id='auto_scan_switch' <?= $auto_scan_out == "true" ? "checked" : "" ?>>
        <label class=' custom-control-label' for='auto_scan_switch'>Auto Scan</label>
      </span>
    </div>
  </div>
  <hr>

  <?php
  $tnkb = $_GET['tnkb'];
  $m_inout_id = $_GET['m_inout_id'];
  ?>

  <div class="row">
    <div class="col-sm-12">

      <div class="row">
        <div class="col-sm">

          <h2 class="text-center text-primary font-weight-bold border" style="font-size:50px" id="jumlah-scan">Loading...</h2>

        </div>

        <div class="col-sm">

          <!-- <div class="row justify-content-center">
                        <div class="col-sm"> -->
          <form method="POST" class="form-scan">
            <!-- <div class="form-group"> -->
            <input type="hidden" name="tnkb" value="<?= $_GET['tnkb']; ?>" class="form-control" readonly>
            <input type="hidden" name="m_inout_id" value="<?= $_GET['m_inout_id']; ?>" class="form-control" readonly>
            <!-- </div> -->
            <div class="input-group">
              <select name="serno" id="select_serno" class="form-control" required>
              </select>
              <div class="input-group-append">
                <button class="btn btn-info" onclick="getScan();" type="button"><i class="fa fa-qrcode"></i></button>
              </div>
              <div class="form-group">
                <input type="hidden" name="m_productasset_id" id="m_productasset_id" class="form-control" readonly>
                <input type="hidden" name="m_product_id" id="m_product_id" class="form-control" readonly>
              </div>
            </div>
          </form>
          <!-- <p class="text-center">
                        <button id="simpan" class="btn btn-success">SIMPAN</button>
                    </p> -->
          <!-- </div>
                    </div> -->

        </div>

        <div class="col-sm">

          <h6 class="text-center font-weight-bold border">
            <?php
            $stid = oci_parse($conn, "SELECT DOCUMENTNO FROM M_INOUT WHERE M_INOUT_ID = '$m_inout_id'");
            oci_execute($stid);
            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            ?>
              <?= strtoupper($row['DOCUMENTNO']); ?><br>
            <?php
            }

            $stid = oci_parse($conn, "SELECT NAME FROM AD_REF_LIST WHERE VALUE = '$tnkb'");
            oci_execute($stid);
            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            ?>
              <?= strtoupper($row['NAME']); ?>
            <?php
            }
            ?>
          </h6>

        </div>
      </div>

      <div class="row">
        <div class="col-md-12 mt-6">
          <table id="datatable" class="display table table-striped small" style="width:100%">
            <thead>
              <tr class="table-primary">
                <th scope="col" style="text-align:left;">NAME</th>
                <th scope="col" style="text-align:left;">SERIAL NUMBER</th>
                <th scope="col" style="text-align:left;">QTY</th>
                <th scope="col" style="text-align:left;">ACTION</th>
              </tr>
            </thead>
            <tbody id="tampil-scan">

            </tbody>
            <tfoot>

            </tfoot>
          </table>
        </div>
      </div>

      <p class="text-center mt-3">
        <a href='index.php?page=surat_jalan&tnkb=<?= $_GET['tnkb']; ?>' class='btn btn-secondary'>KEMBALI</a>
        <button type="button" id="finish" class="btn btn-success" disabled>FINISH</button>
      </p>

    </div>
  </div>

</div>

<!--MODAL HAPUS-->
<div class="modal fade" id="ModalHapus" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="myModalLabel">HAPUS DATA</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
      </div>
      <form method="POST" class="form-horizontal">
        <div class="modal-body">

          <input type="hidden" name="hapus_id" id="hapus_id" value="" readonly>
          <input type="hidden" name="serno" id="serno" value="" readonly>

          <!-- <div class="alert alert-danger"> -->
          <h6>Yakin akan menghapus data?</h6>
          <!-- </div> -->

        </div>
        <div class="modal-footer">
          <button type="button" id="hapus" class="btn btn-danger">YA, HAPUS!</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">TIDAK</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!--END MODAL HAPUS-->

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
          <input type="hidden" name="finish_movemntdate" id="finish_movemntdate" value="" readonly>

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

<script type="text/javascript">
  let $product = $("#select_serno").select2();

  $(document).ready(function() {
    let auto_scan = "<?= $auto_scan_out; ?>";
    let count_auto_scan = 1;

    //Define websocket server
    var Server;
    Server = new FancyWebSocket('ws://192.168.3.245:9300');
    Server.bind('message', function(payload) {
      switch (payload) {
        case 'scaned':
          tampil_scan();
          break;
        case 'reload':
          window.location.href = "index.php";
          break;
        case 'reloadinpage':
          location.reload();
          break;
        case 'change_auto_scan_out':
          location.reload();
          break;
      }
    });
    Server.connect();

    let auto_scan_count_running = 1;

    // Jalankan fungsi tampil_scan
    tampil_scan();

    // Konfigurasi dan menampilkan datatable
    $('#datatable').DataTable({
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

    // Fungsi tampil_scan
    function tampil_scan() {
      var m_inout_id = "<?= $_GET['m_inout_id']; ?>";
      $.ajax({
        type: 'GET',
        url: 'tampil_scan.php',
        data: {
          m_inout_id: m_inout_id
        },
        async: true,
        dataType: 'json',
        success: function(data) {

          var html = '';
          var i;
          for (i = 0; i < data.length; i++) {
            html += '<tr>' +
              '<td style="text-align:left; font-size:35px">' + data[i].NAME_PRODUCTASSET + '</td>' +
              '<td class="align-middle" style="font-size:16px">' + data[i].SERNO + '<br>' + data[i].NAME + '</td>' +
              '<td class="align-middle" style="text-align:left;font-size:20px">' + 1 + '</td>' +
              '<td class="align-middle" style="text-align:left;">' +
              '<a href="javascript:;" class="btn btn-danger btn-sm item_hapus" data="' + data[i].M_PRODUCTASSET_LOAD_ID + '" data-serno="' + data[i].SERNO + '"><i class="fa fa-trash"></i></a>' +
              '</td>' +
              '</tr>';
          }
          html += '<tr>' +
            '<th></th>' +
            '<th style="text-align:left;">JUMLAH</th>' +
            '<th style="text-align:left;">' + i + '</th>' +
            '<th></th>'
          '</tr>';
          $('#tampil-scan').html(html);
          $('#finish').prop("disabled", false);
          $("#jumlah-scan").text(i);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.responseText);
        }
      });
    }

    // Select2
    $('#select_serno').select2({
      theme: "bootstrap",
      placeholder: "Masukan serial number atau nama trolly",
      ajax: {
        url: "select_serno.php",
        type: "post",
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            searchTerm: params.term // search term
          };
        },
        processResults: function(response) {
          return {
            results: response
          };
        },
        cache: true
      },
      minimumInputLength: 3,
    });

    // Jalankan ketika data select2 dipilih.
    // Mengambil data m_productasset_id dan m_product_id dari file cari.php
    // Menggunakan jQuery ajax()
    $('#select_serno').on('select2:select', function(e) {

      let serno = $(this).val();

      $.ajax({
        type: 'POST',
        url: "cari.php",
        data: {
          serno: serno
        },
        dataType: 'json',
        success: function(response) {
          $('#m_productasset_id').val(response[0].M_PRODUCTASSET_ID);
          $('#m_product_id').val(response[0].M_PRODUCT_ID);

          let tnkb = $('[name="tnkb"]').val();
          let m_inout_id = $('[name="m_inout_id"]').val();
          let m_productasset_id = $('[name="m_productasset_id"]').val();
          let m_product_id = $('[name="m_product_id"]').val();

          //prose simpan ke database
          $.ajax({
            type: 'POST',
            url: "simpan_scan.php",
            data: {
              tnkb: tnkb,
              m_inout_id: m_inout_id,
              serno: serno,
              m_productasset_id: m_productasset_id,
              m_product_id: m_product_id
            },
            success: function(data) {

              //clear form
              $('#select_serno').val(null).trigger('change'); //form select serno
              $('#select_serno').val(null); //form select serno
              $('#m_productasset_id').val('');
              $('#m_product_id').val('');
              $('#select_serno').empty();
              tampil_scan();
              Server.send('message', 'scaned'); //kirim message/pesan ke websocket
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(jqXHR.responseText);
            }
          });

        }
      });
    });

    // Jalankan proses simpan ketika tombol simpan diklik
    // 06 Juli 2020: fungsi ini sudah tidak digunakan lagi.
    // fungsi digantikan oleh:
    // fungsi: $('#select_serno').on('select2:select', function(e)({});
    // ketika serno dipilih akan langsung menyimpan data ke database.
    $("#simpan").click(function() {

      var data = $('.form-scan').serialize();

      $.ajax({
        type: 'POST',
        url: "simpan_scan.php",
        data: data,
        success: function(data) {
          $('#m_productasset_id').val('');
          $('#m_product_id').val('');
          $('#select_serno').empty();
          tampil_scan();
          Server.send('message', 'scaned');
        },
        error: function(jqXHR, textStatus, errorThrown) {
          console.log(jqXHR.responseText);
        }
      });
    });

    // Menampilkan konfirmasi hapus data berupa modal bootstrap
    $('#tampil-scan').on('click', '.item_hapus', function() {
      var hapus_id = $(this).attr('data');
      var serno = $(this).attr('data-serno');
      $('#ModalHapus').modal('show');
      $('[name="hapus_id"]').val(hapus_id);
      $('[name="serno"]').val(serno);
    });

    // Proses hapus data
    $("#hapus").click(function() {
      var hapus_id = $('#hapus_id').val();
      var serno = $('#serno').val();
      $.ajax({
        type: 'POST',
        url: "hapus_scan.php",
        data: {
          hapus_id: hapus_id,
          serno: serno
        },
        success: function() {
          $("#ModalHapus").modal('hide');
          tampil_scan();
          // location.reload();
          Server.send('message', 'reloadinpage');
        }
      });
    });

    // Menampilkan konfirmasi finish loading
    $('#finish').click(function() {
      var finish_id = <?= $_GET['m_inout_id']; ?>;
      var finish_movemntdate = "<?= $_GET['movementdate']; ?>";
      $('#ModalFinish').modal('show');
      $('[name="finish_id"]').val(finish_id);
      $('[name="finish_movemntdate"]').val(finish_movemntdate);

    });

    // Proses finish loading
    $('#konfirm_finish').click(function() {
      var finish_id = $('#finish_id').val();
      var finish_movemntdate = $('#finish_movemntdate').val();
      $.ajax({
        type: 'POST',
        url: "finish_scan.php",
        data: {
          finish_id: finish_id,
          finish_movemntdate: finish_movemntdate
        },
        success: function() {
          window.open('./cetak.php?m_inout_id=' + finish_id, '_blank');
          $("#ModalFinish").modal('hide');
          tampil_scan();
          Server.send('message', 'reload');
          Server.send('message', 'update_dashboard');
          window.location.href = "index.php";
        }
      });
    });

    $('#auto_scan_switch').click(function() {

      if ($(this).is(':checked')) {
        auto_scan = "true";
      } else {
        auto_scan = "false";
      }

      //ajax disini untuk mengubah parameter value auto_scan_out           
      $.ajax({
        type: 'POST',
        url: "ajax_change_auto_scan.php",
        data: {
          parameter: "auto_scan_out",
          auto_scan_value: auto_scan
        },
        success: function() {
          //sukses
        }
      });

      console.log(auto_scan);
      Server.send('message', 'change_auto_scan_out');

    });

    function autoScan() {
      let tnkb, m_inout_id, m_productasset_id, m_product_id;

      //cek tombol/switch auto scan apakah on atau off
      //tombol/switch ada di page packing out (bagian atas)
      //START CODE HERE
      if (auto_scan == "true") {

        tnkb = $("[name='tnkb']").val();
        m_inout_id = $("[name='m_inout_id']").val();

        $.ajax({
          type: 'POST',
          url: 'scan_rfid.php',
          data: {
            tnkb: tnkb,
            m_inout_id: m_inout_id
          },
          async: true,
          dataType: 'json',
          success: function(data) {
            tampil_scan();
            console.log('[' + count_auto_scan++ + '] ' + 'auto scan is running...');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
          }
        });

      } else {
        console.log('[' + count_auto_scan++ + '] ' + 'auto scan off');
      }
    }

    //jalankan fungsi autoScan() secara terus menerus
    setInterval(autoScan, 3000);
  });

  //START Code for search for product using Barcode Scanner
  var changingHash = false;

  function onbarcode(event) {
    switch (event.type) {
      case "hashchange": {
        if (changingHash == true) {
          return;
        }
        var hash = window.location.hash;
        if (hash.substr(0, 3) == "#zx") {
          hash = window.location.hash.substr(3);
          changingHash = true;
          window.location.hash = event.oldURL.split("\#")[1] || ""
          changingHash = false;
          processBarcode(hash);
        }

        break;
      }
      case "storage": {
        window.focus();
        if (event.key == "barcode") {
          window.removeEventListener("storage", onbarcode, false);
          processBarcode(event.newValue);
        }
        break;
      }
      default: {
        console.log(event)
        break;
      }
    }
  }
  window.addEventListener("hashchange", onbarcode, false);

  function getScan() {
    var href = window.location.href;
    var ptr = href.lastIndexOf("#");
    if (ptr > 0) {
      href = href.substr(0, ptr);
    }
    window.addEventListener("storage", onbarcode, false);
    setTimeout('window.removeEventListener("storage", onbarcode, false)', 15000);
    localStorage.removeItem("barcode");
    //window.open  (href + "#zx" + new Date().toString());

    if (navigator.userAgent.match(/Firefox/i)) {
      //Used for Firefox. If Chrome uses this, it raises the "hashchanged" event only.
      window.location.href = ("zxing://scan/?ret=" + encodeURIComponent(href + "#zx{CODE}"));
    } else {
      //Used for Chrome. If Firefox uses this, it leaves the scan window open.
      window.open("zxing://scan/?ret=" + encodeURIComponent(href + "#zx{CODE}"));
    }
  }

  function processBarcode(bc) {
    productBarcode(bc);
  }

  function productBarcode(term) {
    $.ajax({
      type: 'GET',
      url: "data/get_packing_out.php?term=" + term,
    }).then(function(data) {

      console.log(data);

      var item = JSON.parse(data);

      if (item.length == 0) {
        console.log('kosong');
      } else {
        console.log('ada');

        var item = JSON.parse(data);

        var option = new Option(item[0].SERNONAME, item[0].SERNO, true, true);

        $product.append(option).trigger('change');

        $product.trigger({
          type: 'select2:select',
          params: {
            data: data
          }
        });
      }
    });
  }
  //END Code for search for product using Barcode Scanner
</script>