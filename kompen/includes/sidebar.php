    <?php
        $dir = basename($_SERVER["REQUEST_URI"]);
        $active_dashboard = ($dir == "dashboard_mhs.php" || $dir == "dashboard_dsn.php") ? "active" : "";
        $active_tugas_terdaftar = ($dir == "tugas_terdaftar.php" || $dir == "tugas_terdaftar.php?filter=belum" || $dir == "tugas_terdaftar.php?filter=selesai" || $dir == "tugas_terdaftar.php?filter=all") ? "active" : "";
        $active_history = ($dir == "history.php") ? "active" : "";

        // mal ini pokoknya buat tampung script JS (Toastr/Redirect)
        // 1. Cek apakah sudah login (Session Location ada)
        include "classes/auth.php";
        $auth = new Auth($db);
        $customScript = "";

        if(isset($_POST["logout"])){
            $logout = $auth->logout();
            header("Location: login.php");
            exit();
        }
    ?>
    <aside id="sidebar" class="bg-white border-end position-fixed top-0 start-0 h-100 d-flex flex-column">
        <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <img src="img/polinela.png" alt="Polinela" style="width: 38px; height: 38px">
                <p class="mb-0 fw-bold text-dark">Kompen Polinela</p>
            </div>
            <button class="btn btn-link text-secondary p-0 d-md-none" onclick="toggleSidebar()">
                <i data-lucide="x" width="24"></i>
            </button>
        </div>

        <nav class="flex-grow-1 p-3 overflow-auto">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="dashboard_mhs.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium <?= $active_dashboard ?>">
                        <i data-lucide="layout-dashboard" width="18"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tugas_terdaftar.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium <?= $active_tugas_terdaftar ?>">
                        <i data-lucide="clipboard-list" width="18"></i> Daftar Tugas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="history.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium <?= $active_history ?>">
                        <i data-lucide="check-square" width="18"></i> Riwayat
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium">
                        <i data-lucide="user" width="18"></i> Profil
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-3 border-top">
            <form method="POST" id="formLogout">
                <button type="submit" id="logout" name="logout"
                    class="btn btn-light w-100 d-flex align-items-center gap-3 px-3 py-2 text-danger rounded-3 fw-medium">
                    <i data-lucide="log-out" width="18"></i> Logout
                </button>
            </form>
        </div>
    </aside>    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#logout").click(function(){
                if(confirm("Apakah Anda yakin ingin logout?")){
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'logout',
                        value: '1'
                    }).appendTo('#formLogout');
                    $("#formLogout").submit();
                }
            });
        });
    </script>
