<?php 
    include 'koneksi.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: index.php");
        exit();
    }
    $nama_user = $_SESSION['username'];

    if (!isset($_GET['id'])) {
        header("location: index.php");
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM tugas WHERE id='$id'";
    $query = mysqli_query($koneksi,$sql);
    $tugas = mysqli_fetch_assoc($query);

    if (mysqli_num_rows($query)<1) {
        die("data tidak di temukan");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php include "title.php"; ?></title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<?php include "favicon.php"; ?>

	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Open+Sans:300,400,600,700"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands"], urls: ['assets/css/fonts.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/azzara.min.css">
	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
	<div class="wrapper">
		<!--
				Tip 1: You can change the background color of the main header using: data-background-color="blue | purple | light-blue | green | orange | red"
		-->
		<div class="main-header" data-background-color="blue">
			<!-- Logo Header -->
			<?php include "header.php";?>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<?php include "navbar.php";?>
			<!-- End Navbar -->
		</div>
		<!-- Sidebar -->
		<?php include "sidebar.php";?>
		<!-- End Sidebar -->


		<div class="main-panel">
			<div class="content">
				<div class="page-inner">
					<div class="page-header">
						<h4 class="page-title">Master Data</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="daftar_tugas.php">Daftar Tugas</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Edit Tugas</a>
							</li>
						</ul>
					</div>

					<div class="row justify-content-center">
						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title">Edit Tugas</div>
								</div>
								<div class="card-body">
									<form action="proses_edit_tugas.php" method="post">
                                        <div class="form-group">
											<input type="hidden" name="id" class="form-control input-square" id="id" value="<?php echo $tugas['id']?>">
										</div>
										<div class="form-group">
											<label for="squareInput">Nama Tugas</label>
											<input type="text" name="nama_tugas" class="form-control input-square" id="nama_tugas" value="<?php echo $tugas['nama_tugas']?>" required>
										</div>
										<div class="form-group">
											<label for="squareInput"> prioritas</label>
                                            <select name="prioritas" id="prioritas" class="form-control input-square">
                                                <option value="Tinggi" <?php if ($tugas['prioritas']=='Tinggi') {echo 'selected';} ?>>Tinggi</option>
                                                <option value="Sedang" <?php if ($tugas['prioritas']=='Sedang') {echo 'selected';} ?>>Sedang</option>
                                                <option value="Rendah" <?php if ($tugas['prioritas']=='Rendah') {echo 'selected';} ?>>Rendah</option>
                                            </select>
										</div>
										<div class="form-group">
											<label for="squareInput">Tanggal</label>
											<input type="date" name="tanggal" class="form-control input-square" id="tanggal" value="<?php echo $tugas['tanggal']?>">
										</div>
									</div>
									<div class="card-action">
										<button type="submit" name="edit_tugas" class="btn btn-success">Submit</button>
										<a href="daftar_tugas.php"><button type="button" class="btn btn-danger">Cancel</button></a>
									</div>
								</form>
							</div>				 
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
		 
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>
	<!-- Bootstrap Toggle -->
	<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
	<!-- Azzara JS -->
	<script src="assets/js/ready.min.js"></script>
	<!-- Azzara DEMO methods, don't include it in your project! -->
	<script src="assets/js/setting-demo.js"></script>
</body>
</html>