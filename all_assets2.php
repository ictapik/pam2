<div class="container">

  <?php
  $value = $_GET['packingid'];
  if ($_GET['status'] == "in") {
    $packing = "ASSET IN";
    $movementtype = "C+";
    $movementdate = "LAS IN";
  } elseif ($_GET['status'] == "out") {
    $packing = "ASSET OUT";
    $movementtype = "C-";
    $movementdate = "LAS OUT";
  } else {
    $packing = "PACKING ALL";
    $movementtype = "C";
    $movementdate = "MOVEMENT DATE";
  }
  ?>

  <?php
  $stid = oci_parse(
    $conn,
    "SELECT value, name FROM M_PRODUCT
        WHERE VALUE = '$value'"
  );

  // Perform the logic of the query
  oci_execute($stid);

  $row = oci_fetch_row($stid);
  ?>

  <br>

  <!-- <div class="table-responsive"> -->
  <table id="datatable" class="display table table-striped table-bordered" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th class="text-center" data-priority="1">NAME</th>
        <th class="text-center">SERNO</th>
        <th class="text-center" data-priority="2">TYPE</th>
        <th class="text-center" data-priority="4">ISACTIVE</th>
        <th class="text-center">LAST TRX</th>
        <?= $_SESSION['isadmin'] == true ? '<th class="text-center notexport" data-priority="3"></th>' : '<th></th>'; ?>
      </tr>
    </thead>
  </table>
  <!-- </div> -->

  <p class="text-center mt-3">
    <a href='index.php?page=assets' class='btn btn-secondary'>KEMBALI</a>
  </p>

</div>

<!-- Bootstrap modal UPDATE ASSET -->
<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">UPDATE ASSET SERNO</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body form">

        <form action="" id="form" class="form-horizontal" autocomplete="off">
          <input type="hidden" value="" name="id" />
          <div class="form-body">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">NAME</label>
              <div class="col-md-9">
                <input name="asset_name" id="asset_name" value="<?= $_GET['name']; ?>" class="form-control" type="text" readonly>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">SERNO</label>
              <div class="col-md-9">
                <input name="asset_serno" id="asset_serno" value="<?= $_GET['serno']; ?>" class="form-control" type="text">
                <span class="help-block"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn_simpan" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Add New Asset modal -->
<div class="modal fade" id="modal_add_asset" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title font-weight-bold">ADD NEW ASSET</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body form">

        <form action="" id="form" class="form-horizontal" autocomplete="off">
          <div class="form-body">
            <div class="form-group row">
              <div class="col-md-9">
                <input name="add_asset_productid" id="add_asset_productid" value="<?= $_GET['productid']; ?>" class="form-control" type="hidden" readonly>
                <input name="add_asset_productid" id="add_asset_createdby" value=" <?= $_SESSION['ad_user_id']; ?>" class="form-control" type="hidden" readonly>
                <input name="add_asset_prefix" id="add_asset_prefix" value="<?= $_GET['prefix']; ?>" class="form-control" type="hidden" readonly>
                <input name="add_asset_prefix" id="add_asset_suffix" value="<?= $_GET['suffix']; ?>" class="form-control" type="hidden" readonly>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">TYPE</label>
              <div class="col-md-9">
                <input name="add_asset_value" id="add_asset_value" value="<?= $_GET['packingid']; ?>" class="form-control" type="text" readonly>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">START</label>
              <div class="col-md-9">
                <input name="add_asset_start" id="add_asset_start" class=" form-control" type="text" placeholder="Asset number start" required>
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">END</label>
              <div class="col-md-9">
                <input name="add_asset_end" id="add_asset_end" class="form-control" type="text" placeholder="Asset number end" required>
                <span class="help-block"></span>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="btn_save_new_asset" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal -->
<!-- End Add New Asset modal -->

<?php
if (isset($_GET['serno'])) {
  $serno = $_GET['serno'];
} else {
  $serno = false;
}
?>

<script type="text/javascript">
  var table;

  $(document).ready(function() {

    //START show edit form 
    var serno = "<?= $serno; ?>";

    if (serno != false) {
      $('#modal_form').modal('show');
    }
    //END show edit form 

    var movementtype = "<?= $movementtype ?>";
    var value = "<?= $value ?>";

    // Setup - add a text input to each footer cell
    $('#datatable tfoot th').each(function() {
      // var title = $(this).text();
      var title = "";
      $(this).html('<input type="text" placeholder="' + title + '" />');
    });

    table = $('#datatable').DataTable({
      "processing": true,
      "serverSide": true,
      "lengthMenu": [
        [10, 25, 50, 100, 500],
        [10, 25, 50, 100, 500]
      ],
      "columnDefs": [{
        "targets": [0, 1, 2, 3, 4],
        "orderable": true,
      }],
      "ajax": {
        "url": "detail_all_assets_data.php",
        "type": "GET",
        "data": {
          "movementtype": movementtype,
          "value": value
        }
      },
      "columns": [{
          "data": null,
          className: "text-center",
          render: function(data, type, row) {
            var packing_id = "<?= $value; ?>";
            var status = "<?= $_GET['status']; ?>";
            return '<a href="index.php?page=log_assets&status=' + status + '&packingid=' + packing_id + '&serno=' + data.serno + '&name=' + row.name + '">' + row.name + '</a>';
          }
        },
        {
          "data": null,
          className: "text-center",
          render: function(data, type, row) {
            var packing_id = "<?= $value; ?>";
            var status = "<?= $_GET['status']; ?>";
            return '<a href="index.php?page=log_assets&status=' + status + '&packingid=' + packing_id + '&serno=' + data.serno + '&name=' + row.name + '">' + data.serno + '</a>';
          }
        },
        {
          "data": "movementtype",
          className: "text-center",
          render: function(data, type, row) {
            let badge;
            if (row.movementtype == 'C-') {
              badge = "danger";
            } else {
              badge = "success";
            }
            return "<span class='badge badge-" + badge + "'>" + row.movementtype + "</span>";
          }
        },
        {
          "data": "isactive",
          className: "text-center",
        },
        {
          "data": "movementdate",
          className: "text-center",
        },
        {
          "data": null,
          className: "text-center",
          render: function(data, type, row) {
            var isadmin = "<?= $_SESSION['isadmin']; ?>";
            var checked_movementtype = row.movementtype == "C+" ? " checked " : "";
            var checked_isactive = row.isactive == "Y" ? " checked " : "";
            var switch_status_icon = row.movementtype == "C+" ? "fa-shipping-fast" : "fa-box-open";
            var switch_status_text = row.movementtype == "C+" ? "Packing Out" : "Packing In";
            var switch_isactive_icon = row.isactive == "Y" ? "fa-times-circle" : "fa-check-circle";
            var switch_isactive_text = row.isactive == "Y" ? "Nonactive" : "Active";


            var switch_status = '<button class="dropdown-item" type="button" onclick="switch_status(\'' + row.serno + '\',\'' + row.movementtype + '\')"><i class="fas ' + switch_status_icon + ' mr-2"></i> ' + switch_status_text + '</button>';

            var switch_isactive = '<button class="dropdown-item" type="button" onclick="switch_isactive(\'' + row.serno + '\',\'' + row.isactive + '\')"><i class="fas ' + switch_isactive_icon + ' mr-2"></i> ' + switch_isactive_text + '</button>';

            var asset_edit = '<button class="dropdown-item" type="button" onclick="getScan(\'' + row.name + '\')"><i class="fas fa-qrcode mr-2"></i> Update Serno</button>';

            var dropdown_start = '<div class="dropdown"><button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></button><div class="dropdown-menu dropdown-menu-right">';

            var dropdown_end = '</div></div>';

            if (isadmin) {
              return dropdown_start + switch_status + switch_isactive + asset_edit + dropdown_end;
            } else {
              return dropdown_start + '<button type="button" class="dropdown-item">Access Denied</button>' + dropdown_end;
            }

          }
        },
      ]
    });

    new $.fn.dataTable.Responsive(table, {
      // details: false
    });

    new $.fn.dataTable.Buttons(table, {
      buttons: [{
          extend: 'copyHtml5',
          text: '<i class="fas fa-copy"></i> Copy',
          titleAttr: 'Copy',
          className: 'btn btn-sm btn-primary mb-2',
          exportOptions: {
            columns: ':not(.notexport)'
          },
        },
        {
          extend: 'excelHtml5',
          text: '<i class="fas fa-file-excel"></i> Excel',
          titleAttr: 'Excel',
          className: 'btn btn-sm btn-primary mb-2',
          exportOptions: {
            columns: ':not(.notexport)'
          },
        },
        {
          extend: 'csvHtml5',
          text: '<i class="fas fa-file-csv"></i> CSV',
          titleAttr: 'CSV',
          className: 'btn btn-sm btn-primary mb-2',
          exportOptions: {
            columns: ':not(.notexport)'
          },
        },
        {
          extend: 'pdfHtml5',
          text: '<i class="fas fa-file-pdf"></i> PDF',
          titleAttr: 'PDF',
          className: 'btn btn-sm btn-primary mb-2',
          exportOptions: {
            columns: ':not(.notexport)'
          },
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i> Print',
          titleAttr: 'Print',
          className: 'btn btn-sm btn-primary mb-2',
          exportOptions: {
            columns: ':not(.notexport)'
          },
        }
      ]
    });

    table.buttons(0, null).container().prependTo(
      table.table().container()
    );

    // Ajax Switch for Assets Status
    $(document).on("change", ".switch-status", function() {
      var serno = $(this).val();
      Swal.fire({
        title: 'Are you sure?',
        text: "Change Asset Status",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            cache: false,
            url: "ajax_change_asset_status.php",
            data: {
              serno: serno
            },
            success: function(data) {
              // if success here
            }
          });
        }
        table.ajax.reload();
      });
    });
    // End Ajax Switch for Assets Status

    // Ajax Switch for Assets Isactive
    $(document).on("change", ".switch-isactive", function() {
      var serno = $(this).val();
      Swal.fire({
        title: 'Are you sure?',
        text: "Change Asset Isactive",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.value) {
          $.ajax({
            type: "POST",
            cache: false,
            url: "ajax_change_asset_isactive.php",
            data: {
              serno: serno
            },
            success: function(data) {
              // if success here
            }
          });
        }
        table.ajax.reload();
      });
    });
    // End Ajax Switch for Assets Isactive

    //START simpan update asset
    $('#btn_simpan').on('click', function() {

      var asset_name = $('#asset_name').val();
      var asset_serno = $('#asset_serno').val();

      $.ajax({
        type: "POST",
        url: "ajax_edit_asset.php",
        // dataType: "JSON",
        data: {
          asset_name: asset_name,
          asset_serno: asset_serno,
        },
        success: function(data) {
          table.ajax.reload();
          Swal.fire({
            position: 'center',
            type: 'success',
            title: 'Asset Updated Successfully',
            showConfirmButton: false,
            timer: 1300
          });
          // $('[name="kobar"]').val("");
          // $('[name="nabar"]').val("");
          // $('[name="harga"]').val("");
          // $('#ModalaAdd').modal('hide');
          // tampil_data_barang();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // alert("ERROE");
          console.log(jqXHR.status);
        }
      });
      $('#modal_form').modal('hide');
      // return false;
    });
    //END simpan update asset

    $('#btn_add_asset').on('click', function() {
      var m_product_id = "<?= $_GET['productid']; ?>";
      var prefix = "<?= $_GET['prefix']; ?>";
      $.ajax({
        type: "POST",
        cache: false,
        dataType: "json",
        url: "ajax_get_last_asset_number.php",
        data: {
          m_product_id: m_product_id,
          prefix: prefix,
        },
        success: function(data) {
          $('#add_asset_start').val(data.next_asset_number);
          $('#modal_add_asset').modal('show');
        }
      });

    });

    $('#btn_save_new_asset').on('click', function() {
      var asset_productid = $('#add_asset_productid').val();
      var asset_createdby = $('#add_asset_createdby').val();
      var asset_prefix = $('#add_asset_prefix').val();
      var asset_suffix = $('#add_asset_suffix').val();
      var asset_start = $('#add_asset_start').val();
      var asset_end = $('#add_asset_end').val();

      if (asset_start != "") {
        $.ajax({
          type: "POST",
          url: "ajax_add_asset.php",
          dataType: "json",
          data: {
            asset_productid: asset_productid,
            asset_createdby: asset_createdby,
            asset_prefix: asset_prefix,
            asset_suffix: asset_suffix,
            asset_start: asset_start,
            asset_end: asset_end,
          },
          success: function(data) {
            console.log(data);
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'Asset Created Successfully',
              showConfirmButton: false,
              timer: 1300
            });
            table.ajax.reload();
            $('#add_asset_start').val('');
            $('#add_asset_end').val('');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.statusText);
          }
        });
        $('#modal_add_asset').modal('hide');
      } else {
        alert("Pastikan semua data terisi");
      }

    });
  });

  function switch_status(serno, last_status) {

    var new_status;
    if (last_status == 'C+') {
      new_status = 'C-';
    } else {
      new_status = 'C+';
    }

    Swal.fire({
      title: 'Are you sure?',
      text: "Change Asset Status",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "POST",
          cache: false,
          url: "ajax_change_asset_status.php",
          data: {
            serno: serno,
            status: new_status
          },
          success: function(data) {
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'Asset Updated Successfully',
              showConfirmButton: false,
              timer: 1300
            });
          }
        });
        table.ajax.reload();
      }
    });
  }

  function switch_isactive(serno, last_isactive) {

    var new_status;
    if (last_isactive == 'Y') {
      new_isactive = 'N';
    } else {
      new_isactive = 'Y';
    }

    console.log(serno);
    console.log(last_isactive);
    console.log(new_isactive);

    Swal.fire({
      title: 'Are you sure?',
      text: "Change Asset Status",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          type: "POST",
          cache: false,
          url: "ajax_change_asset_isactive.php",
          data: {
            serno: serno,
            status: new_isactive
          },
          success: function(data) {
            Swal.fire({
              position: 'center',
              type: 'success',
              title: 'Asset Updated Successfully',
              showConfirmButton: false,
              timer: 1300
            });
          }
        });
        table.ajax.reload();
      }
    });
  }

  var changingHash = false;

  function onbarcode(event) {
    alert('onbarcode');
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

  function getScan(asset_name) {
    console.log("scan...");

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
      // window.open("zxing://scan/?ret=" + encodeURIComponent(href + "#zx{CODE}"));
      window.open("zxing://scan/?ret=" + encodeURIComponent(href + "&name=" + asset_name + "&serno={CODE}"));
    }
  }

  function processBarcode(bc) {
    //document.getElementById("searchid").value = "" + decodeURIComponent(bc);
    document.getElementById("scans").innerHTML += "<div>" + decodeURIComponent(bc) + "</div>";
    findbc(decodeURIComponent(bc));
    //put your code in place of the line above.
  }

  function findbc(id) { //SEPERTINYA TIDAK TERPAKAI
    alert('tampil modal');
    $('#modal_form').modal('show');
  }
</script>