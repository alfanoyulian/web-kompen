<?php
    session_start();
    if (!isset($_SESSION["data"])) {
        header("Location: index.php");
        exit();
    }

    include "classes/databases.php";
    include "classes/tb_tugas.php";
    include "classes/tb_terdaftar.php";
    $id_mhs = $_SESSION["data"]["id"];
    $nama = $_SESSION["data"]["nama_mhs"];

    $db = new Database();
    $terdaftar = new Terdaftar($db);
    $tugas = new Tugas($db, $terdaftar);
    $history_tugas = $tugas->historyTugas($id_mhs);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Polinea Kompen - Dashboard Mahasiswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="custom.css">
</head>
<body>

    <!-- Overlay untuk Mobile -->
    <div id="sidebar-overlay" class="position-fixed top-0 start-0 w-100 h-100" onclick="toggleSidebar()"></div>
    <!-- Sidebar -->
    <?php include'includes/sidebar.php'; ?>
    <!-- Main Content -->
    <main id="main-content" class="min-vh-100 d-flex flex-column">
    <!-- Header -->
    <?php include'includes/header.php'; ?>

        <!-- Content Body -->
        <div class="p-4 p-lg-5 container-fluid">

            <!-- Welcome Banner -->
            <div class="mb-5">
                <h2 class="fw-bold text-dark mb-2">History Kompen yang Sudah Dikerjakan.</h2>
                <p class="text-secondary">Berikut adalah history kompensasi dan tugas yang sudah anda kerjakan.</p>
            </div>
            
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
                                // $kurangi_jam_kompen = $tugas->kurangiJamKompen($id_mhs, $jam_kompen_tugas);
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

        </div>
    </main>

    <!-- Toast Notifikai -->
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050; margin-top: 60px;">
        <div id="liveToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center gap-2">
                    <i id="toast-icon" data-lucide="check-circle" width="20"></i>
                    <span id="toast-message">Notification</span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        lucide.createIcons();
        function filterLocation(value) {
            const cards = document.querySelectorAll('.task-card');
            cards.forEach(c => {
                const loc = c.getAttribute('data-location') || '';
                if (!value) {
                    c.style.display = '';
                } else {
                    c.style.display = (loc === value) ? '' : 'none';
                }
            });
        }

        // Sidebar Toggle Logic
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const overlay = document.getElementById('sidebar-overlay');
            const isMobile = window.innerWidth < 768;

            if (isMobile) {
                sidebar.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            } else {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            }
        }

        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const mainContent = document.getElementById('main-content');

            if (window.innerWidth >= 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
        }
        window.addEventListener('resize', handleResize);
    </script>
</body>
</html>
