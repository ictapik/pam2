<div class="container">
    <h4 class="text-center mt-3 font-weight-bold">DETAIL PENDING PACKING IN</h4>
    <hr>

    <table id="datatable" class="display table table-striped table-bordered" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th class="text-center">NAME</th>
                <th class="text-center">SERNO</th>
                <th class="text-center">GATE</th>
                <th class="text-center">TIME</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stid = oci_parse(
                $conn,
                "SELECT b.NAME, a.SERNO, a.DEVICENAME,
                TO_CHAR(a.CREATED ,'YYYY-MM-DD hh24:mi') CREATED                
                FROM RFID_INVENTORY a
                JOIN M_PRODUCTASSET b ON b.SERNO = a.SERNO
                WHERE b.ISACTIVE = 'Y' AND MOVEMENTTYPE = 'C-'"
            );
            oci_execute($stid);
            while (($row = oci_fetch_array($stid, OCI_ASSOC)) != false) {
            ?>
                <tr>
                    <td><?= $row['NAME']; ?></td>
                    <td><?= $row['SERNO']; ?></td>
                    <td><?= $row['DEVICENAME']; ?></td>
                    <td><?= $row['CREATED']; ?></td>
                    <td style="text-align: center;">
                        <input type="checkbox" name="serno[]" class="cb_serno" value="<?= $row['SERNO']; ?>">
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <button type="button" class="delete-serno btn btn-danger">DELETE</button>
    </div>
</div>

<script>
    $(document).ready(function() {

        let cb_serno = new Array();

        $('#datatable').DataTable({
            responsive: true,
            infoEmpty: false,
            sScrollY: "300",
            pageLength: 100,
            "language": {
                "processing": "Loading...",
                // "sEmptyTable": "Data tidak ditemukan.",
            },
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }],
        });

        $(".cb_serno").click(function() {

            var id = $(this).val();
            var index = $.inArray(id, cb_serno);

            if (index === -1) {
                cb_serno.push(id);
            } else {
                cb_serno.splice(index, 1);
            }

            console.log(cb_serno);
        });

        $(".delete-serno").click(function() {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this data!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'delete_serno.php',
                        data: {
                            cb_serno: cb_serno
                        },
                        async: true,
                        dataType: 'json',
                        success: function(data) {
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    });
                }
            });
        });
    });
</script>