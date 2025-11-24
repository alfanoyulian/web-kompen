<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/azzara.min.css">
    <?php
    include 'koneksi.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("location: index.php");
        exit();
    }
    $nama_user = $_SESSION['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username = '$nama_user'");
    $row = mysqli_fetch_assoc($query);
    $hidden = ($row["role"] == 3) ? "d-none" : "";

    $query_tugas = mysqli_query($koneksi, "SELECT * FROM tugas");
    ?>
</head>

<body>
    <div class="tab-pane fade show active" id="pills-belum-selesai" role="tabpanel" aria-labelledby="pills-belum-selesai-tab">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <h4 class="card-title">Daftar Kegiatan Kompensasi</h4>
                <button class="btn btn-primary btn-round ml-auto <?= $hidden ?> ?>" data-toggle="modal" data-target="#addRowModal">
                    <i class="fa fa-plus"></i>
                    Tambah Tugas
                </button>
            </div>
        </div>
        <table class="table table-bordered mt-3">
            <thead class="table-danger text-center">
                <tr>
                    <th scope="col">NO</th>
                    <th scope="col">DESKRIPSI</th>
                    <th scope="col">LOKASI</th>
                    <th scope="col">JUMLAH JAM</th>
                    <th scope="col">TANGGAL</th>
                    <th scope="col">MAHASISWA DIBUTUHKAN</th>
                    <th scope="col">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                while ($data = mysqli_fetch_assoc($query_tugas)) :
                    $id_tugas = $data['id'];
                    $kuota = $data['mahasiswa_dibutuhkan'];

                    $query_mhs = mysqli_query($koneksi, "SELECT * FROM mhs_terdaftar WHERE id_tugas = '$id_tugas'");
                    $row_mhs = mysqli_num_rows($query_mhs);

                    $persen = ($row_mhs / $kuota) * 100;

                    if ($row_mhs >= $kuota) {
                        $warna = "bg-danger text-white";
                        $btn_disabled = "disabled btn-danger";
                    } elseif ($persen >= 50) {
                        $warna = "bg-warning";
                        $btn_disabled = "btn-success";
                    } else {
                        $warna = "bg-success text-white";
                        $btn_disabled = "btn-success";
                    }
                ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $data['nama_tugas']; ?></td>
                        <td class="text-center"><?= $data['lokasi']; ?></td>
                        <td class="text-center"><?= $data['jumlah_jam']; ?></td>
                        <td><?= $data['tanggal']; ?></td>
                        <td class="text-center">
                            <span class="<?= $warna ?> btn-sm">
                                <?= $row_mhs . "/" . $data['mahasiswa_dibutuhkan']; ?>
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="btn <?= $btn_disabled ?> btn-sm" onclick="show_regist()">Daftar</span>
                            <a href="test_kompen.php?id_tugas=<?= $data['id']; ?>&id_user=<?= $row['no']; ?>"
                                class="btn <?= $btn_disabled ?> btn-sm">
                                Daftar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>

</html>