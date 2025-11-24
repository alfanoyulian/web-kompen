<?php
    session_start();
    if (!isset($_SESSION["data"])) {
        header("Location: login.php");
        exit();
    }

    include "classes/databases.php";
    include "classes/auth.php";
    include "classes/tb_tugas.php";
    include "classes/tb_terdaftar.php";

    $db = new Database();
    $terdaftar = new Terdaftar($db);
    $tugas = new Tugas($db, $terdaftar);
    $auth = new Auth($db);

    $id_mhs = $_SESSION["data"]["id"] ?? 0;
    $nama = $_SESSION["data"]["nama_mhs"] ?? 0;
    $nama = $_SESSION["data"]["nama_mhs"] ?? 0;
    $jam_kompen = $_SESSION["data"]["jam_kompen"] ??"";

    //LOGIC FORMAT JAM:MENIT 
    $jam_kompen = $_SESSION["data"]["jam_kompen"] ?? 0;
    $jam = floor($jam_kompen / 60);
    $menit = $jam_kompen % 60;
    $jmlh_kompen = $jam ." Jam " . $menit . " Menit";
    
    if(isset($_POST["logout"])){
        $logout = $auth->logout();
    }

    // pengiriman formulir: daftar
    if (isset($_POST['daftar'])) {
        $id_tugas = isset($_POST['id_tugas']) ? intval($_POST['id_tugas']) : 0;
        $daftar = $terdaftar->daftarKegiatan($id_mhs, $id_tugas);
        $msg = isset($daftar['message']) ? $daftar['message'] : 'Proses gagal';
        echo "<script>alert('". addslashes($msg) ."'); window.location.href = 'dashboard_mhs.php';</script>";
        exit();
    }

    // ambil data
    $mhs_terdaftar = $terdaftar->mhsTerdaftar($id_mhs);
    $count_terdaftar = isset($mhs_terdaftar['count']) ? $mhs_terdaftar['count'] : 0;

    $alltugas = $tugas->allTugas();
    $count_tugas = isset($alltugas['count']) ? $alltugas['count'] : 0;
    $data_tugas = isset($alltugas['data']) ? $alltugas['data'] : [];

    // menampilkan status bahwa mahasiswa sudah ambil kegiatan tersebut
    $registered_task_ids = [];
    if (isset($mhs_terdaftar['data']) && is_array($mhs_terdaftar['data'])) {
        foreach ($mhs_terdaftar['data'] as $r) {
            if (isset($r['id_tugas'])) $registered_task_ids[] = intval($r['id_tugas']);
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KompenHub - Dashboard Mahasiswa</title>

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
    <?php
        include'includes/sidebar.php';
    ?>

    <!-- Main Content -->
    <main id="main-content" class="min-vh-100 d-flex flex-column">
        <!-- Header -->

        <?php
            include'includes/header.php';
        ?>

        <!-- Content Body -->
        <div class="p-4 p-lg-5 container-fluid">

            <!-- Welcome Banner -->
            <div class="mb-5">
                <h2 class="fw-bold text-dark mb-2">Selamat Datang, <span id="user-firstname"><?= htmlspecialchars($nama ?? 'Mahasiswa') ?></span>! 👋</h2>
                <p class="text-secondary">Berikut adalah status kompensasi dan tugas yang tersedia untuk anda.</p>
            </div>


            <!-- Status Grid -->
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-4">
                    <!-- Stat Card 1 -->
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium small mb-1">Tanggungan Kompen</p>
                                <h3 class="fw-bold text-dark mb-1" id="stat-kompen"><?= $jmlh_kompen ?? 0 ?></h3>
                                <p class="text-secondary small mb-0 opacity-75">Harus diselesaikan segera</p>
                            </div>
                            <div class="p-3 rounded-3 text-white bg-warning d-flex align-items-center justify-content-center shadow-sm">
                                <i data-lucide="clock" width="24"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <!-- Stat Card 2 -->
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium small mb-1">Tugas Tersedia</p>
                                <h3 class="fw-bold text-dark mb-1" id="stat-available"><?= intval($count_tugas) ?></h3>
                                <p class="text-secondary small mb-0 opacity-75">Update 5 menit yang lalu</p>
                            </div>
                            <div class="p-3 rounded-3 text-white bg-primary d-flex align-items-center justify-content-center shadow-sm">
                                <i data-lucide="alert-circle" width="24"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <!-- Stat Card 3 -->
                    <div class="card border-0 shadow-sm rounded-4 h-100 hover-card">
                        <div class="card-body p-4 d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted fw-medium small mb-1">Tugas Terdaftar</p>
                                <h3 class="fw-bold text-dark mb-1" id="stat-registered"><?= intval($count_terdaftar) ?></h3>
                                <p class="text-secondary small mb-0 opacity-75">Menunggu persetujuan admin</p>
                            </div>
                            <div class="p-3 rounded-3 text-white bg-success d-flex align-items-center justify-content-center shadow-sm">
                                <i data-lucide="check-circle" width="24"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary rounded-pill" style="width: 4px; height: 24px;"></div>
                    <h4 class="fw-bold m-0 text-dark">Daftar Tugas Tersedia</h4>
                </div>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm shadow-sm border-0 py-2" onchange="filterLocation(this.value)">
                        <option value="">Semua Lokasi</option>
                        <?php
                        // Build unique locations from $data_tugas
                        $locations = [];
                        foreach ($data_tugas as $dt) {
                            if (!empty($dt['lokasi']) && !in_array($dt['lokasi'], $locations)) $locations[] = $dt['lokasi'];
                        }
                        foreach ($locations as $loc) {
                            echo '<option value="'.htmlspecialchars($loc).'">'.htmlspecialchars($loc).'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Task Grid Container -->
            <div id="tasks-container" class="row g-4">
                <?php if (count($data_tugas) === 0): ?>
                    <div class="col-12 text-center py-5 bg-white rounded-4 border border-dashed border-secondary border-opacity-25">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light mb-3" style="width: 64px; height: 64px">
                            <i data-lucide="alert-circle" class="text-secondary" width="32"></i>
                        </div>
                        <h5 class="fw-medium text-dark">Belum ada tugas tersedia</h5>
                    </div>
                <?php else: ?>
                    <?php foreach ($data_tugas as $task):
                        $task_id = intval($task['id']);
                        // jumlah terdaftar melalui model terdaftar
                        $cnt = $terdaftar->countTerdaftar($task_id);
                        $registered_count = isset($cnt['count']) ? intval($cnt['count']) : (isset($cnt['0'])?intval($cnt[0]):0);
                        $quota = intval($task['kuota'] ?? 0);
                        $percentage = $quota > 0 ? round(($registered_count / $quota) * 100) : 0;
                        $isFull = $quota > 0 && $registered_count >= $quota;
                        $isRegistered = in_array($task_id, $registered_task_ids);
                        $jam_value = intval($task['jumlah_jam'] ?? $task['jam'] ?? 0);
                        $deadline = htmlspecialchars($task['deadline'] ?? '');
                    ?>
                    <div class="col-12 col-md-6 col-xl-3 task-card" data-location="<?= htmlspecialchars($task['lokasi'] ?? '') ?>">
                        <div class="card h-100 border-0 shadow-sm rounded-4 hover-card">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold px-3 py-2">
                                        <?= $jam_value ?> Jam
                                    </span>
                                    <?= $isRegistered ? '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-semibold px-3 py-2">Terdaftar</span>' : '' ?>
                                </div>

                                <h5 class="card-title fw-bold text-dark mb-2 text-truncate" title="<?= htmlspecialchars($task['nama_tugas']) ?>">
                                    <?= htmlspecialchars($task['nama_tugas']) ?>
                                </h5>
                                <p class="card-text text-muted small mb-4" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; height: 60px;">
                                    <?= htmlspecialchars($task['deskripsi']) ?>
                                </p>

                                <div class="mb-4">
                                    <div class="d-flex align-items-center gap-2 text-secondary small mb-2">
                                        <i data-lucide="map-pin" width="16"></i>
                                        <span><?= htmlspecialchars($task['lokasi']) ?></span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 text-secondary small">
                                        <i data-lucide="clock" width="16"></i>
                                        <span>Deadline: <?= $deadline ?></span>
                                    </div>
                                </div>

                                <!-- kuota Progress -->
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between small fw-medium mb-1">
                                        <span class="<?= $isFull ? 'text-danger' : 'text-secondary' ?>">
                                            <i data-lucide="users" width="14" class="me-1 mb-1 d-inline-block"></i>
                                            <?= $registered_count ?>/<?= $quota ?> Mahasiswa
                                        </span>
                                        <span class="text-muted"><?= $percentage ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar rounded-pill <?= $isFull ? 'bg-danger' : 'bg-primary' ?>" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-light bg-opacity-50 border-top border-light p-3 rounded-bottom-4">
                                <form method="POST" class="d-grid">
                                    <input type="hidden" name="id_tugas" value="<?= $task_id ?>">
                                    <?php
                                        $btnDisabled = ($isRegistered || $isFull) ? 'disabled' : '';
                                        $btnClass = ($isRegistered || $isFull) ? 'btn-secondary disabled border-0 bg-secondary bg-opacity-25 text-secondary' : 'btn-primary shadow-sm';
                                        $btnText = $isRegistered ? 'Sudah Diambil' : ($isFull ? 'Kuota Penuh' : 'Ambil Tugas <i data-lucide="arrow-right" width="16" class="ms-1"></i>');
                                    ?>
                                    <button type="submit" name="daftar" <?= $btnDisabled ?> class="btn w-100 d-flex align-items-center justify-content-center gap-2 fw-medium py-2 <?= $btnClass ?>">
                                        <?= $btnText ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

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
