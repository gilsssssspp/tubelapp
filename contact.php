<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak PIC Program</title>

  <!-- Google Font: Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <style>
    /* =========================================================
       GLOBAL FONT (Poppins) — kunci supaya Bootstrap tidak pakai system-ui
       ========================================================= */
    :root{
      --bs-body-font-family: 'Poppins', system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
    }
    body{
      font-family: var(--bs-body-font-family);
    }
    table, th, td, a, button{
      font-family: inherit;
    }

    /* Container utama */
    .table-container {
      background: white;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      position: relative; /* penting agar tombol X bisa menempel */
    }

    /* Tombol X melayang di pojok kanan atas container */
    .close-floating {
      position: absolute;
      top: -15px;
      right: -15px;
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: #ffffff;
      border: 1px solid #cfcfcf;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 20px;
      font-weight: 700;
      color: #444;
      transition: 0.25s;
      z-index: 50;
      user-select: none;
    }

    .close-floating:hover {
      background: #ffebeb;
      border-color: #ff6b6b;
      color: #c90000;
      transform: scale(1.1);
    }

    .logo {
      width: 100px;
      display: block;
      margin: 0 auto 15px auto;
    }

    h2 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 20px;
    }

    th {
      background-color: #0d6efd !important;
      color: white;
    }

    tbody tr:hover {
      background-color: #f1f1f1;
    }

    a {
      text-decoration: none;
    }
    a:hover{
      text-decoration: underline;
    }
  </style>
</head>

<body class="bg-light">

  <div class="container py-5">
    <div class="table-container mx-auto">

      <!-- Tombol X melayang -->
      <div id="btnClose" class="close-floating" aria-label="Tutup">&times;</div>

      <!-- Logo -->
      <img src="assets/img/logo-kontak.png" alt="Logo" class="logo">

      <!-- Judul -->
      <h2>Kontak PIC Program</h2>

      <!-- Tabel -->
      <div class="table-responsive">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Nama</th>
              <th>No Telepon</th>
              <th>Email</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Rahmi Yul</td>
              <td>082325525200</td>
              <td><a href="mailto:kontakPIC-1.s@example.com">kontakPIC-1.s@example.com</a></td>
            </tr>
            <tr>
              <td>Natasya</td>
              <td>081372375774</td>
              <td><a href="mailto:kontakPIC-2.s@example.com">kontakPIC-2.s@example.com</a></td>
            </tr>
          </tbody>
        </table>
      </div>

    </div>
  </div>

  <script>
    // Tombol close kembali ke index
    document.getElementById("btnClose").addEventListener("click", function () {
      window.location.href = "index2.php";
    });

    // Opsional: tombol ESC untuk tutup (lebih enak)
    document.addEventListener("keydown", function(e){
      if (e.key === "Escape") window.location.href = "index.php";
    });
  </script>

</body>
</html>
