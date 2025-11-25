<?php
    session_start();
    include "classes/databases.php";
    include "classes/tb_tugas.php";
    include "classes/tb_terdaftar.php";
    $id_mhs = $_SESSION["data"]["id"];

    $db = new Database();
    $terdaftar = new Terdaftar($db);
    $tugas = new Tugas($db, $terdaftar);
    $history_tugas = $tugas->historyTugas($id_mhs);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Tugas</th>
            <th>Deskripsi</th>
            <th>Lokasi</th>
            <th>Tanggal dan Waktu</th>
            <th>Kuota</th>
            <th>Jumlah Jam</th>
            <th>Status</th>
            <th>Status Tugas</th>
        </tr>
    </thead>
    <tbody>
            <?php 
            if ($history_tugas["count"] > 0){
                $no = 1;
                foreach ($history_tugas["data"] as $row){
                    $jam_kompen_tugas = $row["jumlah_jam"] ?? 0;
                    $jam = floor($jam_kompen_tugas / 60);
                    $menit = $jam_kompen_tugas % 60;
                    $jmlh_jam_tugas = $jam ." Jam " . $menit . " Menit";
                    $kurangi_jam_kompen = $tugas->kurangiJamKompen($id_mhs, $jam_kompen_tugas);
            ?>
                <!-- LOGIC FORMAT JAM:MENIT KOMPEN TUGAS -->
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row["nama_tugas"]; ?></td>
                    <td><?= $row["deskripsi"]; ?></td>
                    <td><?= $row["lokasi"]; ?></td>
                    <td><?= $row["tanggal"]; ?></td>
                    <td><?= $row["kuota"]; ?></td>
                    <td><?= $jmlh_jam_tugas; ?></td>
                    <td><?= htmlspecialchars($row["status"]); ?></td>
                    <td><?= htmlspecialchars($row["status_tugas"]); ?></td>
                </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr>
                <td colspan="5" style="text-align:center;">Tidak ada tugas ACC</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>