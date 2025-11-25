<?php 
    session_start();
    if (!isset($_SESSION["data"])) {
        header("Location: login.php");
        exit();
    }

    include "classes/databases.php";
    include "classes/tb_tugas.php";
    include "classes/tb_terdaftar.php";
    include "includes/icons.php";
    
    $id_mhs = $_SESSION["data"]["id"];
    $nama = $_SESSION["data"]["nama_mhs"];

    $db = new Database();
    $terdaftar = new Terdaftar($db);
    $tugas = new Tugas($db, $terdaftar);
    $tugasTerdaftar = $tugas->tugasTerdaftar($id_mhs);
    
    // Selesaikan Tugas
    if(isset($_POST["selesai"])){
        $id_tugas = $_POST["id_tugas"];
        $selesaikanTugas = $tugas->selesaikanTugas($id_mhs, $id_tugas);
        echo "<script>alert('" . $selesaikanTugas['message'] . "'); location.href='tugas_terdaftar.php';</script>";
    }
    
    // Hapus Tugas
    // if(isset($_POST["hapus"])){
    //     $id_tugas = $_POST["id_tugas"];
    //     $hapusTugas = $tugas->hapusTugas($id_mhs, $id_tugas);
    //     echo "<script>alert('" . $hapusTugas['message'] . "'); location.href='tugas.php';</script>";
    //     echo "<script>alert('Fitur hapus akan segera tersedia'); location.href='tugas.php';</script>";
    // }

    // Filter Logikaaaaaaaaaaaaaa
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
    $filtered_tasks = [];
    
    if(isset($tugasTerdaftar["data"]) && is_array($tugasTerdaftar["data"])){
        foreach ($tugasTerdaftar["data"] as $task) {
            if ($filter === 'all' || $task['status'] === $filter) {
                $filtered_tasks[] = $task;
            }
        }
    }

    // Statistik
    $total = count($tugasTerdaftar["data"] ?? []);
    $completed = count(array_filter($tugasTerdaftar["data"] ?? [], function($t) { 
        return $t['status'] === 'selesai'; 
    }));
    $pending = count(array_filter($tugasTerdaftar["data"] ?? [], function($t) { 
        return $t['status'] === 'belum'; 
    }));

    // Helper Functions
    function formatDuration($minutes) {
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        if ($h > 0 && $m > 0) return "{$h} Jam {$m} Menit";
        if ($h > 0) return "{$h} Jam";
        return "{$m} Menit";
    }

    function formatDate($dateStr) {
        $timestamp = strtotime($dateStr);
        if(!$timestamp) return $dateStr; // Return original if invalid
        
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        
        $dayName = $days[date('w', $timestamp)];
        $day = date('d', $timestamp);
        $month = $months[date('n', $timestamp)];
        $year = date('Y', $timestamp);
        $time = date('H:i', $timestamp);
        
        return "{$dayName}, {$day} {$month} {$year}, {$time}";
    }

    function getStatusBadge($type, $status) {
        $s = strtolower($status);
        $classes = '';
        $label = $status;
        $dot = '';

        if ($type === 'user') {
            if ($s === 'selesai' || $s === 'sudah selesai') {
                $classes = 'bg-success-subtle text-success-emphasis border border-success-subtle';
                $label = 'Selesai';
                $dot = '<span class="d-inline-block rounded-circle me-1 bg-success" style="width: 6px; height: 6px; vertical-align: middle;"></span>';
            } else {
                $classes = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                $label = 'Belum Selesai';
                $dot = '<span class="d-inline-block rounded-circle me-1 bg-warning" style="width: 6px; height: 6px; vertical-align: middle;"></span>';
            }
        } else {
            if ($s === 'tersedia') $classes = 'bg-primary-subtle text-primary-emphasis border border-primary-subtle';
            else if ($s === 'penuh') $classes = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
            else $classes = 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle';
        }

        return "<span class=\"badge rounded-pill fw-medium {$classes}\">{$dot}{$label}</span>";
    }


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Kompen Dashboard - <?= htmlspecialchars($nama) ?></title>
    
    <!-- Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

     <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="custom.css">
    
    <style>
        :root {
            --bs-body-bg: #f9fafb;
            --bs-font-sans-serif: 'Inter', sans-serif;
            --bs-border-color-translucent: rgba(0,0,0,0.06);
        }
        
        body {
            font-family: var(--bs-font-sans-serif);
            background-color: var(--bs-body-bg);
        }

        .fs-7 { font-size: 0.75rem !important; }
        
        .table > :not(caption) > * > * {
            padding: 1rem 1rem;
            background-color: transparent;
        }
        .table-hover tbody tr:hover {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 10;
        }
        
        .filter-btn {
            transition: all 0.2s;
        }
        .filter-btn:hover {
            background-color: rgba(0,0,0,0.03);
        }
    </style>
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
            <div class="min-vh-100">
                <div class="container">   
                    <!-- HEADER -->
                    <div class="row align-items-center mb-4 g-3">
                        <div class="col-12 col-md">
                            <h1 class="h3 fw-bold text-dark mb-1">Daftar Tugas Kompen</h1>
                            <p class="text-secondary mb-0">Halo, <strong><?= htmlspecialchars($nama) ?></strong>. Kelola dan pantau status kompensasi Anda.</p>
                        </div>
                        
                        <div class="col-12 col-md-auto">
                            <div class="d-flex gap-3">
                                <div class="card border-0 shadow-sm rounded-3 px-3 py-2 bg-white">
                                    <span class="d-block fs-7 text-secondary text-uppercase fw-bold">Total</span>
                                    <span class="h5 fw-bold text-dark mb-0"><?= $total ?></span>
                                </div>
                                <div class="card border-0 shadow-sm rounded-3 px-3 py-2 bg-white">
                                    <span class="d-block fs-7 text-secondary text-uppercase fw-bold">Selesai</span>
                                    <span class="h5 fw-bold text-success mb-0"><?= $completed ?></span>
                                </div>
                                <div class="card border-0 shadow-sm rounded-3 px-3 py-2 bg-white">
                                    <span class="d-block fs-7 text-secondary text-uppercase fw-bold">Belum</span>
                                    <span class="h5 fw-bold text-warning mb-0"><?= $pending ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FILTERS -->
                    <div class="card border-0 shadow-sm rounded-3 p-1 d-inline-flex mb-4 bg-white">
                        <div class="d-flex gap-1">
                            <a href="?filter=all" class="btn btn-sm fw-medium px-3 rounded-2 filter-btn <?= $filter === 'all' ? 'bg-primary-subtle text-primary-emphasis shadow-sm' : 'text-secondary' ?>">
                                Semua Tugas
                            </a>
                            <a href="?filter=belum" class="btn btn-sm fw-medium px-3 rounded-2 filter-btn <?= $filter === 'belum' ? 'bg-primary-subtle text-primary-emphasis shadow-sm' : 'text-secondary' ?>">
                                Belum Selesai
                            </a>
                            <a href="?filter=selesai" class="btn btn-sm fw-medium px-3 rounded-2 filter-btn <?= $filter === 'selesai' ? 'bg-primary-subtle text-primary-emphasis shadow-sm' : 'text-secondary' ?>">
                                Selesai
                            </a>
                        </div>
                    </div>

                    <!-- DESKTOP TABLE VIEW -->
                    <div class="card border-0 shadow-sm rounded-3 overflow-hidden d-none d-md-block bg-white">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="bg-light border-bottom">
                                    <tr>
                                        <th class="text-center text-secondary text-uppercase fw-bold fs-7 py-3" style="width: 60px;">No</th>
                                        <th class="text-secondary text-uppercase fw-bold fs-7 py-3">Tugas & Deskripsi</th>
                                        <th class="text-secondary text-uppercase fw-bold fs-7 py-3">Lokasi & Waktu</th>
                                        <th class="text-center text-secondary text-uppercase fw-bold fs-7 py-3">Info</th>
                                        <th class="text-secondary text-uppercase fw-bold fs-7 py-3">Status</th>
                                        <th class="text-end text-secondary text-uppercase fw-bold fs-7 py-3 pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($filtered_tasks)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-secondary">
                                                Tidak ada data tugas untuk filter ini.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php 
                                        $no = 1;
                                        foreach ($filtered_tasks as $row): 
                                            $jam_kompen_tugas = $row["jumlah_jam"] ?? 0;
                                            $jmlh_jam_tugas = formatDuration($jam_kompen_tugas);
                                            $isDone = ($row["status"] == "selesai");
                                            $status_display = ($row["status"] == "belum" ? "belum" : "selesai");
                                        ?>
                                            <tr class="border-bottom">
                                                <td class="text-center text-secondary fw-medium"><?= $no++ ?></td>
                                                <td style="max-width: 300px;">
                                                    <div class="fw-semibold text-dark mb-1"><?= htmlspecialchars($row['nama_tugas']) ?></div>
                                                    <div class="small text-secondary text-truncate" title="<?= htmlspecialchars($row['deskripsi']) ?>">
                                                        <?= htmlspecialchars($row['deskripsi']) ?>
                                                    </div>
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex flex-column gap-1 small">
                                                        <div class="d-flex align-items-center text-dark fw-medium mb-1">
                                                            <span class="text-secondary opacity-75" style="width:20px"><?= $icons['map'] ?></span> 
                                                            <?= htmlspecialchars($row['lokasi']) ?>
                                                        </div>
                                                        <div class="d-flex align-items-center text-secondary">
                                                            <span class="text-secondary opacity-75" style="width:20px"><?= $icons['calendar'] ?></span> 
                                                            <?= formatDate($row['tanggal']) ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-nowrap text-center">
                                                    <div class="d-flex flex-column align-items-center gap-1">
                                                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1">
                                                            <?= $row['kuota'] ?> Slot
                                                        </span>
                                                        <span class="fs-7 text-secondary d-flex align-items-center mt-1">
                                                            <?= $icons['clock'] ?> <?= $jmlh_jam_tugas ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex flex-column align-items-start gap-2">
                                                        <?= getStatusBadge('task', $row['status_tugas']) ?>
                                                        <?= getStatusBadge('user', $status_display) ?>
                                                    </div>
                                                </td>
                                                <td class="text-end text-nowrap pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="id_tugas" value="<?= $row['id'] ?>">
                                                            <button type="submit" name="selesai" class="btn btn-sm d-flex align-items-center fw-medium <?= $isDone ? 'btn-light text-secondary border' : 'btn-primary' ?>"
                                                                <?= $isDone ? 'disabled' : '' ?>>
                                                                <?= $isDone ? $icons['check'] : '' ?> 
                                                                <?= $isDone ? 'Selesai' : 'Selesaikan' ?>
                                                            </button>
                                                        </form>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="id_tugas" value="<?= $row['id'] ?>">
                                                            <button type="submit" name="hapus"
                                                                    class="btn btn-sm btn-light text-secondary border" 
                                                                    title="Hapus"
                                                                    onclick="return confirm('Hapus tugas ini dari daftar?')">
                                                                <?= $icons['trash'] ?>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAMPILAN MOBILE CARD -->
                    <div class="d-md-none">
                        <?php if (empty($filtered_tasks)): ?>
                            <div class="text-center py-5 text-secondary card border-0 bg-transparent">
                                Tidak ada tugas.
                            </div>
                        <?php else: ?>
                            <?php foreach ($filtered_tasks as $row): 
                                $jam_kompen_tugas = $row["jumlah_jam"] ?? 0;
                                $jmlh_jam_tugas = formatDuration($jam_kompen_tugas);
                                $isDone = ($row["status"] == "selesai" || $row["status"] == "sudah selesai");
                                $status_display = ($row["status"] == "belum" ? "belum" : "selesai");
                            ?>
                                <div class="card border-0 shadow-sm rounded-3 mb-3 p-3 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h3 class="h6 fw-bold text-dark mb-1"><?= htmlspecialchars($row['nama_tugas']) ?></h3>
                                            <span class="text-secondary fs-7 d-flex align-items-center">
                                                <?= $icons['map'] ?> <?= htmlspecialchars($row['lokasi']) ?>
                                            </span>
                                        </div>
                                        <?= getStatusBadge('task', $row['status_tugas']) ?>
                                    </div>
                                    
                                    <p class="text-secondary small mb-3 text-truncate"><?= htmlspecialchars($row['deskripsi']) ?></p>
                                    
                                    <div class="border-top pt-3">
                                        <div class="row g-2 text-secondary fs-7 mb-3">
                                            <div class="col-6 d-flex align-items-center">
                                                <?= $icons['calendar'] ?> <?= formatDate($row['tanggal']) ?>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <?= $icons['clock'] ?> <?= $jmlh_jam_tugas ?>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <?= $icons['users'] ?> Kuota: <?= $row['kuota'] ?>
                                            </div>
                                            <div class="col-6 d-flex align-items-center justify-content-end">
                                                <?= getStatusBadge('user', $status_display) ?>
                                            </div>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <form method="POST" class="w-100">
                                                <input type="hidden" name="id_tugas" value="<?= $row['id'] ?>">
                                                <button type="submit" name="selesai"
                                                        class="btn btn-sm w-100 d-flex align-items-center justify-content-center gap-2 fw-medium <?= $isDone ? 'btn-light text-secondary border' : 'btn-primary' ?>"
                                                        <?= $isDone ? 'disabled' : '' ?>>
                                                    <?= $isDone ? $icons['check'] : '' ?> 
                                                    <?= $isDone ? 'Selesai' : 'Selesaikan' ?>
                                                </button>
                                            </form>
                                            <form method="POST">
                                                <input type="hidden" name="id_tugas" value="<?= $row['id'] ?>">
                                                <button type="submit" name="hapus"
                                                        class="btn btn-sm btn-light text-secondary border" 
                                                        style="width: 40px"
                                                        onclick="return confirm('Hapus tugas ini dari daftar?')">
                                                    <?= $icons['trash'] ?>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <!-- FOOTER -->
                    <div class="text-center fs-7 text-secondary mt-5">
                        &copy; 2023 Sistem Informasi Kompensasi.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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