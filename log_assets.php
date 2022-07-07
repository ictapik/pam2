<div class="container">

    <table class="mt-3">
        <tr>
            <th>NAME</th>
            <td>: <?= $_GET['name']; ?></td>
        </tr>
        <tr>
            <th>SERNO</th>
            <td>: <?= $_GET['serno']; ?></td>
        </tr>
    </table>
    <hr>

    <table id="datatable" class="display table table-striped" style="width:100%">
        <thead>
            <tr class="table-primary">
                <th>SURAT JALAN</th>
                <th>TNKB</th>
                <th>DEVICENAME</th>
                <th class="text-center">MOVEMENT TYPE</th>
                <th class="text-center">MOVEMENT DATE</th>
            </tr>
        </thead>
    </table>

    <p class="text-center mt-3"> 
		<span class='btn btn-secondary' onclick="goBack()">KEMBALI</span>
    </p>

</div>

<script>
	function goBack() {
		window.history.back();
	}
	
    $(document).ready(function() {

        let serno = "<?= $_GET['serno']; ?>";

        $('#datatable').DataTable({
            responsive: true,
            // searching: false,
            // paging: false,
            // infoEmpty: false,
            // ordering: false,
            // info: false,
            "order": [
                [3, "DESC"]
            ],
            // "language": {
            //     "processing": "Loading...",
            //     "sEmptyTable": "Data tidak ditemukan.",
            // }
            "sScrollY": 280,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "log_assets_data.php",
                "type": "GET",
                "data": {
                    "serno": serno
                }
            },
            "columns": [{
                    "data": "documentno",
                },
                {
                    "data": "tnkb",
                },
                {
                    "data": "devicename",
                },
                {
                    className: "text-center",
                    "data": "movementtype",
                },
                {
                    className: "text-center",
                    "data": "movementdate",
                },
            ]
        });
    });
</script>