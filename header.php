<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Packing System</title>
	<link rel="shortcut icon" href="./assets/img/favicon.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <!-- Datatables -->
    <link rel="stylesheet" href="./assets/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Datatable Responsive -->
    <link rel="stylesheet" href="./assets/responsive.dataTables.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="./assets/select2/css/select2.min.css">
    <link rel="stylesheet" href="./assets/select2-bootstrap-theme/css/select2-bootstrap.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="./assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Datatable Select Row -->
    <link rel="stylesheet" href="./assets/select.bootstrap4.min.css">

    <!-- AdminLTE -->
    <!-- Sedikit tambahan saja. -->
    <link rel="stylesheet" href="./assets/adminlte/adminlte.css">

    <!-- My Style -->
    <link rel="stylesheet" href="./assets/style.css">

    <!-- jQuery -->
    <script src="./assets/jquery-3.4.1.min.js"></script>
    <!-- Popper JS -->
    <script src="./assets/plugins/popper/popper.min.js"></script>
    <!-- Bootstrap -->
    <script src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <!-- Datatable -->
    <script src="./assets/plugins/datatables/jquery.dataTables.js"></script>
    <script src="./assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <!-- Datatable Responsive -->
    <script src="./assets/dataTables.responsive.min.js"></script>
    <!-- Select2 -->
    <script src="./assets/select2/js/select2.min.js"></script>
    <!-- SweetAlert -->
    <script src="./assets/sweetalert2/sweetalert2.all.min.js"></script>
    <!-- Fancywebsocket -->
    <script src="./websocket/fancywebsocket.js"></script>

    <!-- JAVASCRIPT UNTUK DATATABLES BUTTON (Copy, Excel, CSV, PDF, Print) -->
    <script src="./assets/dataTables.buttons.min.js"></script>
    <script src="./assets/buttons.flash.min.js"></script>
    <script src="./assets/jszip.min.js"></script>
    <script src="./assets/pdfmake.min.js"></script>
    <script src="./assets/vfs_fonts.js"></script>
    <script src="./assets/buttons.html5.min.js"></script>
    <script src="./assets/buttons.print.min.js"></script>

    <!-- JAVASCRIPT UNTUK DATATABLE SELECT ROW -->
    <script src="./assets/dataTables.select.min.js"></script>

</head>

<body>
    <nav class=" navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="./"><b>PACKING SYSTEM</b></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav mr-auto">
                    <a class="nav-item nav-link active" href="index.php"><i class="fas fa-home"></i> Home</a>
                    <a class="nav-item nav-link active" href="index.php?page=packing_in"><i class="fas fa-box-open"></i> Packing In</a>
                    <a class="nav-item nav-link active" href="index.php?page=tnkb"><i class="fas fa-shipping-fast"></i> Packing Out</a>
                    <!-- <a class="nav-item nav-link active" href="index.php?page=history"><i class="fas fa-history"></i> History</a> -->
                    <li class="nav-item dropdown">
                        <a class="nav-link active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-history"></i> History
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="index.php?page=history_packing_in">Packing In</a>
                            <a class="dropdown-item" href="index.php?page=history_packing_out">Packing Out</a>
                        </div>
                    </li>
                    <a class="nav-item nav-link active" href="index.php?page=assets"><i class="fas fa-dolly-flatbed"></i> Assets</a>
                </div>
                <div class="navbar-nav my-2 my-lg-0">
                    <span class="nav-item nav-link active">
                        <i class="fa fa-user"></i> <?= $_SESSION['name']; ?>
                    </span>
                    <a class="nav-item nav-link active" href="logout.php">
                        <i class="fa fa-power-off"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>