<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Login</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/icon.ico" type="image/x-icon"/>
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
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/azzara.min.css">
</head>
<body class="login">
	<div class="wrapper wrapper-login">
		<div class="container container-login animated fadeIn">
			<h3 class="text-center">Sign In To Admin</h3>
			<form method="POST">
				<div class="login-form">
					<div class="form-group">
						<label for="username" class="placeholder"><b>Username</b></label>
						<input id="username" name="email" type="text" class="form-control" required>
					</div>
					<div class="form-group">
						<label for="password" class="placeholder"><b>Password</b></label>
						<div class="position-relative">
							<input id="password" name="password" type="password" class="form-control" required>
							<div class="show-password">
								<i class="flaticon-interface"></i>
							</div>
						</div>
					</div>
					<div class="form-group form-action-d-flex justify-content-center mb-3">
						<button type="submit" name="login" class="btn btn-primary col-md-5 float-right mt-3 mt-sm-0 fw-bold">Login</button>
					</div>
				</div>
			</form>
		</div>
		<?php
			session_start();
			include 'koneksi.php';
			if (isset($_POST['login'])) {
				$email = $_POST['email'];
				$password = $_POST['password'];

				$stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE email=? AND password=?");
				$stmt->bind_param("ss",$email,$password);
				$stmt->execute();
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
					$data = mysqli_fetch_assoc($result);
					$_SESSION['username'] = $data['username'];
					echo "<script>alert('Login Berhasil'); location.href='dashboard.php';</script>";
					exit;
				}else{
					echo "<script>alert('username atau password anda salah coba lagi!')</script>";
				}
			}
			if (isset($_SESSION['username'])) {
				header("Location: dashboard.php");
				exit;
				}
		?>
	</div>
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	<script src="assets/js/ready.js"></script>
</body>
</html>