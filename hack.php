<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sorgulama | Logsuzlar Stabil System</title>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --mainColor: #2196f3;
      --black: #000000;
      --white: #FFFFFF;
      --whiteSmoke: #C4C3CA;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Lato', sans-serif;
      margin: 0;
      background-color: var(--black);
      color: var(--white);
    }

    .back-button {
      position: fixed;
      top: 20px;
      left: 20px;
      background-color: var(--mainColor);
      color: white;
      border: none;
      padding: 10px 14px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      z-index: 999;
      transition: 0.3s;
    }

    .back-button:hover {
      background-color: var(--white);
      color: var(--mainColor);
    }

    .container {
      max-width: 900px;
      margin: 100px auto 40px;
      padding: 0 20px;
      text-align: center;
    }

    h1, h2 {
      text-align: center;
      margin-bottom: 30px;
      color: var(--white);
    }

    input, button {
      padding: 12px;
      margin: 10px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      background-color: #333;
      color: white;
      transition: 0.3s;
    }

    input {
      width: 80%;
      max-width: 400px;
    }

    input::placeholder {
      color: #aaa;
    }

    button {
      background-color: var(--mainColor);
      color: white;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color: var(--white);
      color: var(--mainColor);
    }

    #result, #map {
      margin-top: 20px;
      border: 1px solid #333;
      padding: 10px;
      border-radius: 6px;
      display: inline-block;
      min-width: 250px;
      color: var(--whiteSmoke);
    }

    #map {
      height: 150px;
      width: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .info {
      text-align: left;
      margin-top: 40px;
      padding: 20px;
      border: 1px solid #333;
      border-radius: 6px;
    }

    .info h2 {
      margin-top: 0;
      text-align: left;
    }

    .info p {
      margin: 10px 0;
      color: var(--whiteSmoke);
    }

    .info a {
      color: var(--mainColor);
      text-decoration: none;
      transition: 0.3s;
    }

    .info a:hover {
      color: var(--white);
    }

    .photo {
      display: inline-block;
      vertical-align: middle;
      width: 100px;
      margin: 10px;
    }

    .photo img {
      width: 100%;
      border-radius: 5px;
    }

    .premium-link { /* New class for the premium button inside result/map */
      display: none; /* Initially hidden */
      margin-top: 15px; /* Spacing from the text */
      padding: 10px 15px;
      background-color: #0088cc; /* Telegram blue */
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: 0.3s;
      display: inline-block; /* To allow margin-top and proper sizing */
    }

    .premium-link:hover {
      background-color: #005f99; /* Darker Telegram blue on hover */
    }
  </style>
</head>
<body>
  <a href="javascript:history.back()" class="back-button"><</a>
  <div class="container">
    <h1>INSTAGRAM HESAP ŞİFRE BULUCU</h1>
    <div>
      <div class="photo">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTy1EqXFUlfZ_jPww4Yx7qAiLlL-cJ6CJYeHQ&s" alt="Instagram">
      </div>
      <input type="text" id="username" placeholder="Kullanıcı Adını Girin">
      <button onclick="checkUsername()">Sorgula</button>
    </div>
    <div id="result"></div>

    ---

    <h1>Canlı Konum Bulma</h1>
    <div>
      <div class="photo">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ-FrSCeokOFxsSumdPKRUeamqyw2MyArN9Jw&s" alt="Konum">
      </div>
      <input type="text" id="locationPhone" placeholder="Telefon Numarası (Ülke Kodu ile)">
      <button onclick="getLocation()">Konumu Göster</button>
    </div>
    <div id="map"></div>
    
    ---

    <div class="info">
      <h2>Logsuzlar</h2>
      <h2>Nasıl Kullanılır?</h2>
      <p>Instagram kullanıcı adı sorgulamak için yukarıdaki alana kullanıcı adınızı girin ve "Sorgula" butonuna tıklayın. Sorgulama işlemi tamamlandığında sonuçlar premium üyelik Gereklidir.</p>
      <p>Canlı konum bilgisi almak için telefon numaranızı girin ve "Konumu Göster" butonuna tıklayın. Konum bilgisi gösterilmeden önce premium üyelik gereklidir.</p>
      <h2>VIP Üye Nasıl Olur?</h2>
      <p>VIP üye olabilmek için sitemizin premium üyelik seçeneklerini inceleyin. Üyelik avantajları arasında daha hızlı sorgulama, özel hizmetler ve canlı konum bilgileri bulunmaktadır.</p>
      <p>Üyelik almak için <a href="https://t.me/Yucemiz" target="_blank">buraya</a> tıklayarak üyelik sayfasına ulaşabilirsiniz.</p>
      <p>Üyelik Almak İçin Sizi Yönlendirdiğimiz Telegram Adresinden Bize Ulaşınız</p>
    </div>
  </div>
  <script>
    function checkUsername() {
      var resultDiv = document.getElementById('result');
      
      resultDiv.innerHTML = 'Kişi sorgulanıyor...';
      
      setTimeout(function() {
        resultDiv.innerHTML = 'Veri bulundu...';
        
        setTimeout(function() {
          resultDiv.innerHTML = 'Premium Üyeliğiniz Bulunmamaktadır.<br><a href="https://t.me/Yucemiz" target="_blank" class="premium-link">Üyelik Almak İçin Tıklayın (Telegram)</a>';
        }, 2000);
      }, 3000);
    }

    function getLocation() {
      var locationPhone = document.getElementById('locationPhone').value;
      var mapDiv = document.getElementById('map');

      if (locationPhone) {
        mapDiv.innerHTML = 'Konum bulunuyor...';
        
        setTimeout(function() {
          mapDiv.innerHTML = 'Veri bulundu...';
          
          setTimeout(function() {
            mapDiv.innerHTML = 'Premium Üyeliğiniz Bulunmamaktadır.<br><a href="https://t.me/Yucemiz" target="_blank" class="premium-link">Üyelik Almak İçin Tıklayın (Telegram)</a>';
          }, 2000);
        }, 3000);
      } else {
        alert('Konum bilgisi almak için telefon numarası girmeniz gerekmektedir.');
      }
    }
  </script>
</body>
</html>