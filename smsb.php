<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logsuzlar Checker</title>
    <link rel="canonical" href="https://newtonbulem.000webhostapp.com" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3; /* Ana mavi renk */
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

        .username-box {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #111;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            z-index: 99999;
        }

        .username-box button {
            margin-left: 10px;
            padding: 5px 10px;
            background-color: rgba(255, 0, 0, 0.5);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .username-box button:hover {
            background-color: rgba(255, 0, 0, 0.7);
        }

        /* YENİ '<' ŞEKLİNDEKİ GERİ BUTONU STİLLERİ */
        .top-left-back-button {
            position: fixed;
            top: 15px; /* Üstten boşluk */
            left: 15px; /* Soldan boşluk */
            z-index: 100000;
        }

        .top-left-back-button button {
            background-color: var(--mainColor); /* BUTONUN ARKA PLANI MAVİ YAPILDI! */
            color: var(--white); /* BUTONUN İÇİNDEKİ '<' SİMGESİ BEYAZ YAPILDI! */
            border: none; /* Kenarlık yok */
            padding: 5px 8px; /* İç boşlukları daralt */
            border-radius: 8px; /* Köşeleri yuvarla */
            font-size: 24px; /* Simgenin boyutunu büyüt */
            cursor: pointer;
            font-weight: 700; /* Daha kalın bir '<' için */
            line-height: 1; /* Dikey hizalama için */
            transition: background-color 0.2s ease;
            display: flex; /* İçeriği hizalamak için flex kullan */
            align-items: center; /* Dikeyde ortala */
            justify-content: center; /* Yatayda ortala */
            height: 35px; /* Buton yüksekliğini ayarla */
            width: 35px; /* Buton genişliğini ayarla */
        }

        .top-left-back-button button:hover {
            /* Hover'da biraz daha koyu mavi veya hafif şeffaf hale getirilebilir */
            background-color: #1976D2; /* mainColor'ın biraz daha koyu tonu */
            /* Alternatif olarak: background-color: rgba(33, 150, 243, 0.8); */
        }
        /* YENİ '<' ŞEKLİNDEKİ GERİ BUTONU STİLLERİ SONU */

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            width: 300px;
            margin: 100px auto 40px; /* Adjusted margin to center content vertically */
            background-color: transparent; /* Removed background color for the main container */
            border-radius: 6px;
            padding: 0 20px; /* Adjusted padding */
        }

        .container input,
        .container button {
            margin-bottom: 15px; /* Increased margin-bottom for better spacing */
            padding: 12px; /* Increased padding */
            border-radius: 6px;
            border: none; /* Removed border */
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            background-color: #333; /* Dark background for inputs */
            color: white;
        }

        .container input::placeholder {
            color: #aaa; /* Placeholder color */
        }

        .container button {
            background-color: var(--mainColor); /* Blue background for button */
            color: white;
            cursor: pointer;
            transition: 0.3s; /* Smooth transition */
            font-weight: bold; /* Bold text for button */
        }

        .container button:hover {
            background-color: white; /* White background on hover */
            color: var(--mainColor); /* Blue text on hover */
        }

        h1 {
            text-align: center; /* Center align the heading */
            margin-bottom: 30px; /* Increased margin-bottom */
            color: var(--white);
            font-size: 24px; /* Increased font size for heading */
        }

        .result-container {
            margin-top: 10px;
            padding: 15px;
            width: 300px; /* Set a fixed width to match the input container */
            margin: 20px auto; /* Center the result container */
            background-color: rgba(28, 28, 28, 0.9); /* Original background for result container */
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 20px;
        }

        .result-container .smsStatus {
            color: #111;
            padding: 10px;
            background-color: #fff;
            margin-top: 5px;
            font-size: 16px;
            text-align: center;
            width: calc(100% - 20px); /* Adjust width to account for padding */
            box-sizing: border-box;
        }

        .result-container .smsstat {
            color: #fff;
            text-align: center;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .result-container .not {
            text-align: center;
            margin-top: 10px;
            color: #ccc; /* Slightly lighter color for the note */
            font-size: 14px;
        }

        .statcontainer-wrap {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }

        .additional-container {
            display: none;
            width: 500%;
            max-width: 900px;
            max-height: 80vh;
            background-color: rgba(28, 28, 28);
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 15px 20px;
            overflow-y: auto;
        }

        .statusMessages {
            color: #fff;
            font-size: 20px;
        }

        @media (max-width: 600px) {
            .container, .result-container {
                width: 90%;
            }

            h1 {
                font-size: 20px;
            }

            input, .container button, .result-container .smsStatus {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="top-left-back-button">
        <button onclick="goBack()">&lt;</button>
    </div>
    <div class="container">
        <h1>Logsuzlar Checker</h1>
        <input type="text" id="phoneNumber" placeholder="Telefon numarası">
        <input type="number" id="smsAmount" placeholder="SMS miktarı">
        <input type="number" id="workerAmount" placeholder="Threads miktarı">
        <button onclick="start()">Gönder</button>
    </div>
    <div class="result-container">
        <label class="smsstat">SMS Durumu:</label>
        <div class="smsStatus" id="smsStatus"></div>
        <div id="result"></div>
        <pre class="not">Not: Thread 50-100 arası Önerilir.</pre>
    </div>
    <div class="statcontainer-wrap">
        <div id="additional-container" class="additional-container">
            <div id="statusMessages"></div>
        </div>
    </div>
    <script src="Logsuz.js"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        // Example for start function (replace with your actual logic from Logsuz.js)
        function start() {
            const phoneNumber = document.getElementById('phoneNumber').value;
            const smsAmount = document.getElementById('smsAmount').value;
            const workerAmount = document.getElementById('workerAmount').value;
            const smsStatusDiv = document.getElementById('smsStatus');

            if (phoneNumber && smsAmount && workerAmount) {
                smsStatusDiv.textContent = `Gönderiliyor: ${phoneNumber} (${smsAmount} SMS, ${workerAmount} thread)`;
                // Add your actual SMS sending logic here
            } else {
                smsStatusDiv.textContent = "Lütfen tüm alanları doldurun.";
            }
        }
    </script>
    <div class="footer">
        &copy; Logsuzlar Checker
    </div>
</body>
</html>