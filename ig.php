<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Instagram Premium</title>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      background: #000;
      font-family: 'Orbitron', sans-serif;
      color: #0ff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      flex-direction: column;
    }

    .container {
      text-align: center;
      background: rgba(255, 255, 255, 0.05);
      padding: 30px;
      border: 2px solid #0ff;
      border-radius: 15px;
      box-shadow: 0 0 20px #0ff;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      margin-bottom: 20px;
    }

    .header img {
      width: 60px;
    }

    .header span {
      font-size: 22px;
      color: #0ff;
      text-shadow: 0 0 10px #0ff;
    }

    input {
      padding: 10px;
      width: 250px;
      border: none;
      outline: none;
      background: #111;
      color: #0ff;
      border: 2px solid #0ff;
      border-radius: 10px;
      font-size: 16px;
      margin-bottom: 20px;
    }

    button {
      padding: 10px 25px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      border-radius: 10px;
      background: #0ff;
      color: #000;
      box-shadow: 0 0 10px #0ff;
      transition: 0.3s;
    }

    button:hover {
      background: #00cccc;
      box-shadow: 0 0 20px #0ff;
    }

    .popup {
      display: none;
      margin-top: 20px;
      background: #111;
      padding: 20px;
      border-radius: 10px;
      border: 1px solid #0ff;
      box-shadow: 0 0 10px #0ff;
    }

    .telegram {
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="header">
    <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram Logo">
    <span>Instagram Şifre Bulucu</span>
  </div>

  <input type="text" id="username" placeholder="Instagram Adı Giriniz"><br>
  <button onclick="showPopup()">Şifre Bul</button>

  <div class="popup" id="popupBox">
    <p><strong>Premium üyeliğiniz bulunmamaktadır.</strong></p>
    <a href="https://t.me/hayalsikerim" target="_blank">
      <button class="telegram">Satın Al</button>
    </a>
  </div>
</div>

<script>
  function showPopup() {
    const username = document.getElementById("username").value.trim();
    if (username === "") {
      alert("Lütfen bir kullanıcı adı gir.");
      return;
    }
    document.getElementById("popupBox").style.display = "block";
  }
</script>

</body>
</html>