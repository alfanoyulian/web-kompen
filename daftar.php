<?php
    include 'koneksi.php';

    if (isset($_POST['sign_up'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "INSERT INTO tb_user (email,username,password) VALUES ('$email','$username','$password')";
        $query = mysqli_query($koneksi,$sql);

        if ($query) {
            echo "<script>alert('SING UP BERHASIL'); location.href='index.php';</script>";
            exit();
        } else {
            echo "daftar gagal";
        }
    }
?>