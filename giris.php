<?php
session_start();
if (isset($_SESSION['giris']) && $_SESSION['giris'] === true) {
  header("Location: index.php");
  exit();
}

$hata = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $kod = trim($_POST["aktivasyon_kodu"]);
  if ($kod === "LogsuzStabilKey") {
    $_SESSION['giris'] = true;
    header("Location: index.php");
    exit();
  } else {
    $hata = "Geçersiz aktivasyon kodu.";
  }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="favicon.png">
  <title>Logsuzlar Stabil System | Giriş</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
  <style>
    :root {
      --mainColor: #2196f3;
      --black: #000000;
      --white: #FFFFFF;
      --whiteSmoke: #C4C3CA;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Lato', sans-serif;
      font-size: 16px;
      background-color: var(--black);
      color: var(--white);
    }
    .container {
      max-width: 1080px;
      margin: auto;
      padding: 0 20px;
    }
    .full-screen {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 140px 20px 40px;
    }
    nav {
      width: 100%;
      background-color: var(--black);
      position: fixed;
      z-index: 999;
      padding: 25px;
      top: 0;
      left: 0;
    }
    nav .logo {
      color: var(--white);
      font-size: 32px;
      font-weight: 600;
      text-transform: capitalize;
    }
    nav .logo span {
      color: var(--mainColor);
    }
    h2 {
      font-size: 40px;
      margin-bottom: 30px;
      text-align: center;
    }
    .login-box {
      background: #111;
      padding: 40px;
      border-radius: 10px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 0 15px rgba(255, 255, 255, 0.05);
      text-align: center;
    }
    input[type="text"] {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 8px;
      margin-bottom: 20px;
      background-color: #222;
      color: var(--white);
      font-size: 16px;
    }
    .btn {
      height: 44px;
      padding: 0 30px;
      background-color: var(--mainColor);
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      text-transform: uppercase;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      color: var(--white);
      border: none;
      cursor: pointer;
      width: 100%;
      transition: all .2s ease;
    }
    .btn:hover {
      background-color: var(--white);
      color: var(--mainColor);
    }
    .error {
      color: #f44336;
      margin-bottom: 15px;
      font-size: 14px;
    }
    @media (max-width: 768px) {
      h2 { font-size: 28px; }
    }
  </style>
</head>
<body>

<nav>
  <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="logo">Logsuz<span>lar</span></div>
  </div>
</nav>

<section class="full-screen">
  <div class="container">
    <h2>Logsuzlar Stabil System | Giriş</h2>
    <div class="login-box">
      <?php if ($hata): ?>
        <div class="error"><?php echo $hata; ?></div>
      <?php endif; ?>
      <form method="POST">
        <input type="text" name="aktivasyon_kodu" placeholder="Aktivasyon Kodunuz">
        <button type="submit" class="btn">Giriş Yap</button>
      </form>
    </div>
  </div>
</section>

</body>
</html>
