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
            <th>Jam Kompen</th>
            <th>Kuota</th>
            <th>Status</th>
            <th>Action</th>
        </thead>
        <tbody>
            <?php
                $no = 1;
                foreach($tugasTerdaftar["data"] as $t){
                $status = ($t["status"] == "belum" ? "Belum Selesai" : "Sudah Selesai");
                $btn_disabled = ($t["status"] == "belum" ? "" : "disabled");
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $t["nama_tugas"]; ?></td>
                <td><?= $t["deskripsi"]; ?></td>
                <td><?= $t["lokasi"]; ?></td>
                <td><?= $t["jumlah_jam"]; ?></td>
                <td><?= $t["kuota"]; ?></td>
                <td><?= $status; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_tugas" value="<?= $t["id"] ?>">
                        <button type="submit" name="selesai" <?= $btn_disabled ?>>Selesaikan</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>