<?php
    include 'koneksi.php';

    if (isset($_GET['no'])) {
        $no = $_GET['no'];

        $sql = "DELETE FROM tb_user WHERE no='$no'";
        $query = mysqli_query($koneksi,$sql);

        if ($query) {
            header("location: datausers.php?hapus=success");
            exit();
        }else{
            die ("gagal menghapus");
        }
    }
?>