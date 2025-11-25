<?php
    session_start();
    include "classes/databases.php";
    include "classes/tb_tugas.php";
    include "classes/tb_terdaftar.php";
    $id_mhs = $_SESSION["data"]["id"];

    $db = new Database();
    $terdaftar = new Terdaftar($db);
    $tugas = new Tugas($db, $terdaftar);
    $tugasTerdaftar = $tugas->tugasTerdaftar($id_mhs);
    if(isset($_POST["selesai"])){
        $id_tugas = $_POST["id_tugas"];
        $selesaikanTugas = $tugas->selesaikanTugas($id_mhs , $id_tugas);
        echo "
        <script>alert('" . $selesaikanTugas['message'] . "'); location.href='tugas.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border="1">
        <thead>
            <th>No</th>
            <th>Tugas</th>
            <th>Deskripsi</th>
            <th>Lokasi</th>
            <th>Tanggal dan Waktu</th>
            <th>Kuota</th>
            <th>Jam Kompen</th>
            <th>status</th>
            <th>Status Tugas</th 
            <th>Status</th>
        </thead>
        <tbody>
            <?php
                $no = 1;
                foreach($tugasTerdaftar["data"] as $row){
                    $jam_kompen_tugas = $row["jumlah_jam"] ?? 0;
                    $jam = floor($jam_kompen_tugas / 60);
                    $menit = $jam_kompen_tugas % 60;
                    $jmlh_jam_tugas = $jam ." Jam " . $menit . " Menit";

                    $status = ($row["status"] == "belum" ? "Belum Selesai" : "Sudah Selesai");
                    $status_tugas = $row["status_tugas"];
                    $btn_disabled = ($row["status"] == "belum" ? "" : "disabled");
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row["nama_tugas"]; ?></td>
                <td><?= $row["deskripsi"]; ?></td>
                <td><?= $row["lokasi"]; ?></td>
                <td><?= $row["tanggal"]; ?></td>
                <td><?= $row["kuota"]; ?></td>
                <td><?= $jmlh_jam_tugas; ?></td>
                <td><?= $status_tugas; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_tugas" value="<?= $t["id"] ?>">
                        <button type="submit" name="selesai" <?= $btn_disabled ?>>Selesaikan</button>
                        <button type="submit" name="">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>