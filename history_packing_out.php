<div class=" container">
    <h4 class="text-center mt-3">HISTORY PACKING OUT</h4>
    <hr>

    <div class="row">
        <div class="col-sm-12">
            <table id="datatable" class="display table table-striped" style=" width:100%">
                <thead>
                    <tr class="table-primary">
                        <th scope="col" style="text-align: left;">SURAT JALAN</th>
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
            "ajax": "history_packing_out_data.php",
            "columnDefs": [{
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }],
            "order": [
                [2, "DESC"]
            ],
            "columns": [{
                    "data": "documentno",
                    render: function(data, type, row) {
                        return '<b>' + row.documentno + '</b><br>' + row.name;
                    }
                },
                {
                    "data": "tnkb",
                },
                {
                    "data": "created",
                },
                {
                    "data": null,
                    className: "text-center",
                    render: function(data, type, row) {
                        return '<a href="index.php?page=detail_history_packing_out&m_inout_id=' + row.id + '" class="btn btn-success btn-sm"><i class="fa fa-eye"></i></a> <a href="cetak.php?m_inout_id=' + row.id + '" target="_blank" class="btn btn-warning btn-sm"><i class="fa fa-print"></i></a>';
                    }
                },
            ],
        });
    });
</script>