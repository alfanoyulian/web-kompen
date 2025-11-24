<?php 
    include 'koneksi.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: index.php");
        exit();
    }
    $nama_user = $_SESSION['username'];

    if (!isset($_GET['no'])) {
        header("location: datausers.php");
    }

    $no = $_GET['no'];
    $sql = "SELECT * FROM tb_user WHERE no='$no'";
    $query = mysqli_query($koneksi,$sql);
    $d_user = mysqli_fetch_assoc($query);

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
								<a href="datausers.php">Data Users</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Edit Data</a>
							</li>
						</ul>
					</div>
					<div class="row">
 
						<div class="col-md-6">
						
							<div class="card">
								<div class="card-header">
									<div class="card-title">Tambah Data</div>
								</div>
								<div class="card-body">
									<form action="proses_edit.php" method="post">
                                        <div class="form-group">
											<input type="hidden" name="no" class="form-control input-square" id="no" value="<?php echo $d_user['no']?>">
										</div>
										<div class="form-group">
											<label for="squareInput">Email</label>
											<input type="email" name="email" class="form-control input-square" id="email" placeholder="Email" value="<?php echo $d_user['email']?>" required>
										</div>
										<div class="form-group">
											<label for="squareInput"> UserName</label>
											<input type="text" name="username" class="form-control input-square" id="username" placeholder="User Name" value="<?php echo $d_user['username']?>" required>
										</div>
												
										<div class="form-group">
											<label for="squareInput">Password</label>
											<input type="password" name="password" class="form-control input-square" id="password" placeholder="Password" value="<?php echo $d_user['password']?>" required>
                                            <div class="show-password">
                                                <i class="flaticon-interface" style="position: absolute;
												right: 40px; top: 69%; transform: translateY(-50%); font-size: 22px;
												cursor: pointer;">
												</i>
                                            </div>
										</div>
									</div>
									<div class="card-action">
										<button type="submit" name="edit" class="btn btn-success">Submit</button>
										<a href="datausers.php"><button type="button" class="btn btn-danger">Cancel</button></a>
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