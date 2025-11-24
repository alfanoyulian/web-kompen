<?php 
    include 'koneksi.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $query = mysqli_query($koneksi,"UPDATE tugas SET status='Selesai' WHERE id='$id'");
        if ($query) {
            echo "<script>alert('Tugas Selesai'); location.href='daftar_tugas.php';</script>";
        }else{
            echo "gagal";
        }
    }
?>