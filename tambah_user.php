<?php
    include 'koneksi.php';
   
   if (isset($_POST['tambah'])) {
	$nama_user = $_POST['nama_user'];
	$username = $_POST['username'];
	$password = $_POST['password'];

	$sql = "INSERT INTO tb_user (nama_user,username,password) VALUES ('$nama_user','$username','$password')";
	$query = mysqli_query($koneksi,$sql);

	if ($query) {
		header("location: datausers.php?daftar=berhasil");
		exit();
	} else {
		echo "daftar gagal";
	}
}
						
?>
