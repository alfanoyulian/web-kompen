<?php 
    include 'koneksi.php';

    if (isset($_POST['edit_tugas'])) {
        $id = $_POST['id'];
        $nama_tugas = $_POST['nama_tugas'];
        $prioritas = $_POST['prioritas'];
        $tanggal = $_POST['tanggal'];

        $sql = "UPDATE tugas SET nama_tugas='$nama_tugas',prioritas='$prioritas',tanggal='$tanggal' WHERE id='$id'";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            echo "<script>alert('Tugas Berhasil di Edit'); location.href='daftar_tugas.php';</script>";
        } else {
            echo "Gagal: " . mysqli_error($koneksi);
        }
    }
?>