<?php
    include 'koneksi.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "DELETE FROM tugas WHERE id='$id'";
        $query = mysqli_query($koneksi,$sql);

        if ($query) {
            echo "<script>alert('Tugas Berhasil Di hapus'); location.href='daftar_tugas.php';</script>";
            exit();
        }else{
            die ("gagal menghapus");
        }
    }
?>