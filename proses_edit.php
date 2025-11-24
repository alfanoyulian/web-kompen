<?php
include 'koneksi.php';

if (isset($_POST['edit'])) {
    $no = $_POST['no'];
    $nama_user = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "UPDATE tb_user SET email='$email', username='$username', password='$password' WHERE no='$no'";
    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        header("Location: datausers.php");
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>
