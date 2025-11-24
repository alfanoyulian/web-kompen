        <header class="bg-white border-bottom sticky-top px-4 py-3 d-flex align-items-center justify-content-between shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <button onclick="toggleSidebar()" class="btn btn-light text-secondary border-0 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                    <i data-lucide="menu" width="24"></i>
                </button>
                <h5 class="m-0 fw-bold text-dark d-none d-sm-block">Dashboard Mahasiswa</h5>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="position-relative">
                    <i data-lucide="bell" width="20" class="text-secondary"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-3 ps-4 border-start">
                    <div class="text-end d-none d-md-block">
                        <p class="m-0 fw-bold small text-dark" id="user-name"><?= htmlspecialchars($nama ?? 'Mahasiswa') ?></p>
                        <p class="m-0 small text-muted" style="font-size: 11px" id="user-nim"><?= htmlspecialchars($_SESSION['data']['npm'] ?? '') ?></p>
                    </div>
                    <img id="user-avatar" src="<?= htmlspecialchars($_SESSION['data']['avatar'] ?? 'https://via.placeholder.com/40') ?>" alt="Profile" class="rounded-circle border shadow-sm" style="width: 40px; height: 40px; object-fit: cover;">
                </div>
            </div>
        </header>