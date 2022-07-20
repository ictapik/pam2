<div class="container">
  <br>
  <table id="datatable" class="mt-3 display table table-striped table-bordered" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th class="text-center" data-priority="1">NAME</th>
        <th class="text-center">SERNO</th>
        <th class="text-center" data-priority="2">TYPE</th>
        <th class="text-center">ISACTIVE</th>
        <th class="text-center">LAST TRX</th>
        <?= $_SESSION['isadmin'] == true ? '<th class="text-center notexport" data-priority="3"></th>' : '<th></th>'; ?>
      </tr>
    </thead>
    <tfoot>
      <tr class="table-primary">
        <th class="text-center">NAME</th>
        <th class="text-center">SERNO</th>
        <th class="text-center">TYPE</th>
        <th class="text-center">ISACTIVE</th>
        <th class="text-center">LAST TRX</th>
        <th class="text-center">ACTION</th>
      </tr>
    </tfoot>
  </table>
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
          <input type="text" value="" name="id" />
          <div class="form-body">
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">NAME</label>
              <div class="col-md-9">
                INPUT ASSET NAME
                <span class="help-block"></span>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-sm-3 col-form-label">SERNO</label>
              <div class="col-md-9">
                INPUT SERNO
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

<script type="text/javascript">
  var table;

  $(document).ready(function() {

    table = $('#datatable').DataTable({
      "processing": true,
      "serverSide": true,
      "columnDefs": [{
        "targets": [0, 1, 2, 3, 4],
        "orderable": true,
      }],
      "ajax": {
        "url": "detail_all_assets_data.php",
        "type": "GET",
        "data": {}
      },
      "columns": [{
        "data": "name",
        className: "text-center",
      }, {
        "data": "serno",
        className: "text-center",
      }, {
        "data": "movementtype",
        className: "text-center",
        render: function(data, type, row) {
          let badge;
          if (row.movementtype == 'C-')
            badge = "danger";
          else
            badge = "success";
          return "<span class='badge badge-" + badge + "'>" + row.movementtype + "</span>";
        }
      }, {
        "data": "isactive",
        className: "text-center",
      }, {
        "data": "movementdate",
        className: "text-center",
      }, {
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
      }, ]
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
    var href = window.location.href;
    var ptr = href.lastIndexOf("#");
    if (ptr > 0) {
      href = href.substr(0, ptr);
    }
    window.addEventListener("storage", onbarcode, false);
    console.log("scan...hlo");
    setTimeout('window.removeEventListener("storage", onbarcode, false)', 1000);
    localStorage.removeItem("barcode");
    //window.open  (href + "#zx" + new Date().toString());

    if (navigator.userAgent.match(/Firefox/i)) {
      //Used for Firefox. If Chrome uses this, it raises the "hashchanged" event only.
      console.log("scan...1");
      window.location.href = ("zxing://scan/?ret=" + encodeURIComponent(href + "#zx{CODE}"));
    } else {
      console.log("scan...2 start");
      console.log('href = ' + href);
      //Used for Chrome. If Firefox uses this, it leaves the scan window open.
      // window.open("zxing://scan/?ret=" + encodeURIComponent(href + "#zx{CODE}"));
      window.open("zxing://scan/?ret=" + encodeURIComponent(href + "&name=" + asset_name + "&serno={CODE}"));
      console.log("scan...2 start");

    }
  }

  function processBarcode(bc) {
    console.log('process barcode');
    //document.getElementById("searchid").value = "" + decodeURIComponent(bc);
    document.getElementById("scans").innerHTML += "<div>" + decodeURIComponent(bc) + "</div>";
    alert(decodeURIComponent(bc));
    findbc(decodeURIComponent(bc));
    //put your code in place of the line above.
  }

  function findbc(id) { //SEPERTINYA TIDAK TERPAKAI
    alert('tampil modal');
    $('#modal_form').modal('show');
  }
</script>