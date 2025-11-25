    <aside id="sidebar" class="bg-white border-end position-fixed top-0 start-0 h-100 d-flex flex-column">
        <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-3 bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 32px; height: 32px">K</div>
                <h5 class="mb-0 fw-bold text-dark">KompenHub</h5>
            </div>
            <button class="btn btn-link text-secondary p-0 d-md-none" onclick="toggleSidebar()">
                <i data-lucide="x" width="24"></i>
            </button>
        </div>

        <nav class="flex-grow-1 p-3 overflow-auto">
            <ul class="nav flex-column gap-2">
                <li class="nav-item">
                    <a href="dashboard_mhs.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium active">
                        <i data-lucide="layout-dashboard" width="18"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="tugas_terdaftar.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium">
                        <i data-lucide="clipboard-list" width="18"></i> Daftar Tugas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="history.php" class="nav-link w-100 d-flex align-items-center gap-3 px-3 py-2 rounded-3 fw-medium">
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
            <form method="POST">
                <button type="submit" name="logout" class="btn btn-light w-100 d-flex align-items-center gap-3 px-3 py-2 text-danger rounded-3 fw-medium">
                    <i data-lucide="log-out" width="18"></i> Logout
                </button>
            </form>
        </div>
    </aside>