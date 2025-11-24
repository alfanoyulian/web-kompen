<?php 
include 'koneksi.php';

if (isset($_GET['id_tugas']) && isset($_GET['id_user'])) {

    $id_tugas = $_GET['id_tugas'];
    $id_user  = $_GET['id_user'];

    // Query insert — sesuaikan field tabel mhs_terdaftar
    $query = mysqli_query($koneksi, 
        "INSERT INTO mhs_terdaftar (id_tugas, id_mhs) VALUES ('$id_tugas', '$id_user')");

    if ($query) {
        echo "<script>
                alert('Berhasil mendaftar tugas.');
                location.href='test.php';
              </script>";
    } else {
        echo "Gagal: " . mysqli_error($koneksi);
    }
}
?>
