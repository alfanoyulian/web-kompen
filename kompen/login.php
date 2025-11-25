<?php
session_start();

// MAL KALO NGODING DI KASIH KOMEN BIAR TAU WOE!!

// mal ini pokoknya buat tampung script JS (Toastr/Redirect)
$customScript = "";

// 1. Cek apakah sudah login (Session Location ada)
if (isset($_SESSION['location'])) {
    $loc = $_SESSION["location"];
    // Tampilkan notifikasi info terus redirect
    $customScript = "
        toastr.info('Anda sudah login, mengalihkan...', 'Info');
        setTimeout(function() { window.location.href = '$loc'; }, 2000);
    ";
}

// 2. Proses Login saat Form disubmit
if (isset($_POST['login']) && empty($customScript)) {
    
    if (file_exists('classes/databases.php') && file_exists('classes/auth.php')) {
        include 'classes/databases.php';
        include 'classes/auth.php';
        
        $db = new Database();
        $auth = new Auth($db);
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $user = $auth->login($username, $password);
        
        if ($user) {
            $role = $user['role'];
            $_SESSION['role'] = $role;
            $_SESSION['data'] = $user['data'];
            $_SESSION['location'] = $user['location'];
            
            $msg = $user["message"];
            $loc = $user["location"];
            
            // Toastr SUKSES
            $customScript = "
                toastr.success('$msg', 'Login Berhasil');
                setTimeout(function() { window.location.href = '$loc'; }, 2000);
            ";
        } else {
            // Toastr GAGAL
            $customScript = "toastr.error('Username atau Password salah.', 'Login Gagal');";
        }
    } else {
        $customScript = "toastr.warning('File backend tidak ditemukan.', 'System Error');";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body class="bg-white d-flex justify-content-center align-items-center min-vh-100" style="font-family: 'Inter', sans-serif;">

    <!-- Main Card -->
    <div class="card border-0 shadow-lg overflow-hidden" style="width: 100%; max-width: 400px; border-radius: 24px;">
        
        <!-- Header Section  -->
        <div class="text-center p-5 position-relative" style="background-color: #4F46E5;">
            <!-- Overlay skibidi -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(rgba(255,255,255,0.2) 1px, transparent 1px); background-size: 20px 20px; opacity: 0.5; pointer-events: none;"></div>
            
            <!-- Avatar -->
            <div class="d-flex align-items-center justify-content-center mx-auto mb-3 rounded-circle position-relative z-1" 
                 style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(4px);">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            
            <!-- judul -->
            <h1 class="h4 text-white fw-bold mb-1 position-relative z-1">Kompen Polinela</h1>
            <p class="text-white-50 mb-0 small position-relative z-1">"Lebih baik terlihat cupu daripada jadi cepu"</p>
        </div>

        <!-- Body -->
        <div class="card-body p-4 pt-4">
            
            <form action="" method="POST" id="loginForm">
                
                <!-- Username Input -->
                <div class="mb-4">
                    <label for="username" class="form-label fw-medium text-secondary small">NPM</label>
                    <div class="position-relative">
                        <!-- Icon -->
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary opacity-50 pe-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        
                        <input type="text" class="form-control bg-light border-light-subtle py-3 ps-5 rounded-3" 
                               id="username" name="username" placeholder="Masukkan NPM Anda" required autocomplete="username">
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-medium text-secondary small">Password</label>
                    <div class="position-relative">
                        <!-- Icon -->
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary opacity-50 pe-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        
                       
                        <input type="password" class="form-control bg-light border-light-subtle py-3 ps-5 rounded-3" 
                               id="password" name="password" placeholder="Masukkan password Anda" required autocomplete="current-password">
                        
                        <!-- Toggle Buttooooon -->
                        <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2 text-secondary opacity-75" id="togglePassword">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeOffIcon" class="d-none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="login" class="btn text-white w-100 py-2 rounded-3 fw-semibold shadow-sm mt-2" 
                        style="background-color: #4F46E5; border-color: #4F46E5;">
                    Masuk Sekarang
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted" style="font-size: 0.75rem;">&copy; 2025 Politeknik Negeri Lampung</small>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        // Config Toastr
        toastr.options = { 
            "closeButton": true, 
            "progressBar": true, 
            "positionClass": 
            "toast-top-right", 
            "timeOut": "2000" 
        };

        // Toggle Password Logika
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('password');
            const iconEye = document.getElementById('eyeIcon');
            const iconOff = document.getElementById('eyeOffIcon');
            
            if (input.type === 'password') {
                input.type = 'text';
                iconEye.classList.add('d-none');
                iconOff.classList.remove('d-none');
            } else {
                input.type = 'password';
                iconEye.classList.remove('d-none');
                iconOff.classList.add('d-none');
            }
        });

        // Backend PHP Integration
        $(document).ready(function() {
            <?php echo $customScript; ?>
        });
    </script>
</body>
</html>