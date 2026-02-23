<?php
session_start(); // [DIUBAH] agar bisa baca pesan gagal dari loginproses.php
?>
<!DOCTYPE html>
<html lang="id">

<!-- head -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>

    <!-- Css Bootstrap -->
    <link rel="stylesheet" href="styles/bootstrap/css/bootstrap.min.css">
    <!-- font-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Css Login -->
    <link rel="stylesheet" href="styles/login.css">

    <!-- [DIUBAH] SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- [DIUBAH] -->
</head>

<!-- script hide & show password -->
<script>
function togglePassword() {
    const input = document.getElementById('pass');
    const eye = document.getElementById('icon-eye');
    const eyeOff = document.getElementById('icon-eye-off');

    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';

    eye.style.display = isHidden ? 'none' : 'inline';
    eyeOff.style.display = isHidden ? 'inline' : 'none';
}
</script>

<!-- body -->
<body>

    <!-- [DIUBAH] SweetAlert muncul jika ada pesan gagal -->
        <?php if (!empty($_SESSION['login_error'])): ?>
        <!-- [DIUBAH] SweetAlert auto-close + fokus password (tanpa progress bar) -->
        <script>
        document.addEventListener('DOMContentLoaded', function () {
          Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: <?= json_encode($_SESSION['login_error']); ?>,

            // auto close
            timer: 2200,
            showConfirmButton: false,   // tidak ada tombol
            allowOutsideClick: false,   // tidak bisa klik luar
            timerProgressBar: false,    // ❌ progress bar dimatikan

            // branding
            background: '#ffffff',
            color: '#1f2937',

            customClass: {
              popup: 'kmdgri-swal-popup'
            },

            // fokus ke input password setelah alert hilang
            didClose: () => {
              const passInput = document.getElementById('pass');
              if (passInput) passInput.focus();
            }
          });
        });
        </script>

        <!-- [DIUBAH] Styling branding (tanpa progress bar) -->
        <style>
          .kmdgri-swal-popup {
            border-radius: 14px;
          }
        </style>

        <?php unset($_SESSION['login_error']); endif; ?>



    <!-- container -->
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="row justify-content-center p-5">
                <div class="col-md-6 d-none d-md-block p-5">
                <div class="login-image"
                    data-logo1="images/logokdn.svg"
                    data-logo2="images/login2.svg">

                    <!-- tombol kiri -->
                    <button type="button" class="logo-nav prev" aria-label="Sebelumnya">‹</button>

                    <!-- tombol kanan -->
                    <button type="button" class="logo-nav next" aria-label="Berikutnya">›</button>
                </div>
            </div>

                <div class="col-md-6 p-5">
                    
                    <!-- form login -->
                    <form method="POST" action="loginproses.php" class="user">
                        <h1 class="text-center mb-5">Halaman Login</h1>
                        
		     <!-- CSRF Token -->
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] = bin2hex(random_bytes(32)) ?>">

                    <!-- input username -->
                    <div class="mb-3">
                        <input type="text" class="form-control form-control-user" id="name" name="name"placeholder="Username">
                    </div>

                    <!-- input password -->
                    <div class="mb-3 position-relative">
                        <input type="password"class="form-control form-control-user"id="pass"name="pass"placeholder="Password">
                        <button type="button"class="toggle-password"aria-label="Tampilkan/Sembunyikan password"onclick="togglePassword()">
                    <!-- Eye icon  -->
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>

                    <!-- Eye-off slash  -->
                    <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <path d="M3 3l18 18"></path>
                            <path d="M10.58 10.58A3 3 0 0 0 12 15a3 3 0 0 0 2.42-4.42"></path>
                            <path d="M9.88 5.1A10.43 10.43 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1-2.3 3.3"></path>
                            <path d="M6.1 6.1C3.9 8 2 12 2 12s3.5 7 10 7a10.43 10.43 0 0 0 3.1-.5"></path>
                    </svg>
                    </button>
                </div>

                    <!-- tombol login -->
                    <div class="mb-3 text-center d-grid gap-md-2 mx-auto">
                        <button type="submit" class="btn btn-login" name="submit">Log In</button>
                    </div>
                    <hr/>

                    <!-- register -->
                    <p class=" text-center ">Kembali ke <a class="text-decoration-none" href="index.php">Home</a></p>
					<p class=" text-center ">Login menggunakan username dan password Simpeg Anda</p>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
include 'footer.php';
?>

<script>
  const el = document.querySelector('.login-image');
  if (!el) {
    console.warn('Elemen .login-image tidak ditemukan');
  } else {
    const logos = [el.dataset.logo1, el.dataset.logo2].filter(Boolean);
    let current = 0;

    // tampilkan logo awal
    el.style.backgroundImage = `url("${logos[current]}")`;

    function show(index) {
      current = (index + logos.length) % logos.length;
      el.style.backgroundImage = `url("${logos[current]}")`;
    }

    function next() { show(current + 1); }
    function prev() { show(current - 1); }

    // tombol panah
    const btnPrev = el.querySelector('.logo-nav.prev');
    const btnNext = el.querySelector('.logo-nav.next');
    btnPrev?.addEventListener('click', (e) => { e.stopPropagation(); prev(); resetAuto(); });
    btnNext?.addEventListener('click', (e) => { e.stopPropagation(); next(); resetAuto(); });

    // klik area: kiri = prev, kanan = next
    el.addEventListener('click', (e) => {
      const rect = el.getBoundingClientRect();
      const x = e.clientX - rect.left;
      (x < rect.width / 2) ? prev() : next();
      resetAuto();
    });

    // autoplay
    let timer = setInterval(next, 5000);

    function resetAuto() {
      clearInterval(timer);
      timer = setInterval(next, 5000);
    }

    // opsional: pause saat hover (biar terasa profesional)
    el.addEventListener('mouseenter', () => clearInterval(timer));
    el.addEventListener('mouseleave', () => { resetAuto(); });
  }
</script>
