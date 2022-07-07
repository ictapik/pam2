<div class=" container">
    <h4 class="text-center mt-3">HISTORY PACKING IN</h4>
    <hr>

    <div class="row">
        <div class="col-sm-12">
            <table id="datatable" class="display table table-striped" style=" width:100%">
                <thead>
                    <tr class="table-primary">
                        <th scope="col" style="text-align: left;">TNKB</th>
                        <th scope="col" style="text-align: left;">TANGGAL</th>
                        <th scope="col" style="text-align: left;">ACTION</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "sScrollY": "350",
            "ajax": "history_packing_in_data.php",
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }],
            "order": [
                [1, "DESC"]
            ],
            "columns": [{
                    "data": "tnkb",
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
                        return '<a href="index.php?page=detail_history_packing_in&tnkb=' + row.tnkb + '&movementdate=' + row.movementdate_ind + '" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a>';
                    }
                },
            ]
        });
    });
</script>