<?php
// PHP tarafında herhangi bir oturum veya doğrulama yapmıyoruz,
// çünkü token ve ID kullanıcı tarafından girilecek.
// Bu dosya doğrudan erişilebilir.
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hack Link Oluşturucu | Logsuzlar Stabil System</title>
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

        /* Geri butonu stilleri */
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: transparent; /* Arkaplanı transparan yap */
            color: var(--white); /* Beyaz yazı (ok işareti) */
            border: 2px solid var(--mainColor); /* Mavi çerçeve */
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            z-index: 999;
            transition: all 0.3s ease; /* Yumuşak geçişler */
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px; /* Kare buton için */
            height: 40px; /* Kare buton için */
            font-size: 1.2em; /* Ok işareti için daha büyük font */
        }

        .back-button:hover {
            background-color: var(--mainColor); /* Hover'da mavi arkaplan */
            color: var(--white); /* Hover'da beyaz yazı */
            border-color: var(--white); /* Hover'da beyaz çerçeve */
        }

        /* Yeni eklenen stil: Sabit başlık alanı */
        .header-fixed {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #1a1a1a; /* Arka plan rengi */
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
            z-index: 990; /* İçeriklerin altında ama geri butonunun üstünde */
            display: flex;
            justify-content: flex-end; /* İçeriği sağa yasla */
            align-items: center;
        }


        .container {
            max-width: 700px;
            /* margin-top değeri, header-fixed yüksekliğine göre ayarlandı (60px + 40px = 100px) */
            margin: 100px auto 40px;
            padding: 0 20px;
            background-color: #1a1a1a;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            position: relative; /* İçindeki mutlak konumlu elemanlar için */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            padding-top: 30px;
            color: var(--white);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding-bottom: 30px;
        }

        input {
            padding: 12px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
            background-color: #333;
            color: white;
            width: 100%;
        }

        input::placeholder {
            color: #aaa;
        }

        .btn {
            background-color: var(--mainColor);
            color: white;
            font-weight: bold;
            border: none;
            padding: 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }

        .btn:hover {
            background-color: white;
            color: var(--mainColor);
        }

        .link-display-area {
            margin-top: 30px;
            padding: 20px;
            background-color: #2c2c2c;
            border-radius: 8px;
            text-align: center;
            word-wrap: break-word; /* Uzun linklerin taşmasını engeller */
        }

        #apiLinkOutput {
            font-size: 1.1em;
            color: #f0f0f0;
            margin-bottom: 15px;
            word-break: break-all; /* Özellikle uzun tokenler için */
        }

        .copy-button {
            background-color: #4CAF50; /* Yeşil renk */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .copy-button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Nasıl Kullanılır butonu ve penceresi için yeni stiller */
        .help-button-fixed { /* Butonu ekranın sağ üstüne sabitle */
            position: fixed;
            top: 20px; /* Geri butonuyla aynı hizada */
            right: 20px;
            background-color: var(--mainColor); /* Mavi renk */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9em;
            transition: 0.3s;
            z-index: 1000; /* Her zaman en üstte */
        }

        .help-button-fixed:hover {
            background-color: #1976d2; /* Daha koyu mavi */
        }

        .help-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.7);
            z-index: 1000;
            width: 80%;
            max-width: 400px;
            display: none;
            border: 1px solid #444;
            color: var(--white);
            text-align: center;
        }

        .help-popup h3 {
            margin-top: 0;
            color: var(--mainColor);
            margin-bottom: 15px;
        }

        .help-popup p {
            margin-bottom: 10px;
            line-height: 1.4;
            font-size: 0.95em;
        }
        .help-popup a {
            color: var(--mainColor);
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .help-popup a:hover {
            color: var(--white);
        }

        .help-popup .close-button {
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.8em;
            cursor: pointer;
            font-weight: bold;
        }

        .help-popup .close-button:hover {
            color: var(--mainColor);
        }

        /* Arka plan karartma */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 999;
            display: none;
        }


        @media (max-width: 600px) {
            input, .btn, .copy-button {
                font-size: 14px;
                padding: 10px;
            }
            .container {
                margin-top: 50px; /* Header'ın yüksekliğine göre ayarlandı */
            }
            .back-button {
                top: 10px;
                left: 10px;
                width: 35px;
                height: 35px;
                font-size: 1em;
            }
            .help-button-fixed { /* Mobil için de aynı hizayı koru */
                top: 10px;
                right: 10px;
                padding: 6px 10px;
                font-size: 0.8em;
            }
            .help-popup {
                width: 95%;
                padding: 15px;
            }
            .help-popup .close-button {
                font-size: 1.5em;
            }
            .header-fixed {
                padding: 10px 15px;
            }
        }
    </style>
</head>
<body>

    <div class="header-fixed">
        <a href="javascript:history.back()" class="back-button"><</a>
        <button id="helpButtonFixed" class="help-button-fixed">Nasıl Kullanılır?</button>
    </div>

    <div class="container">
        <h2>Hack Link Oluşturucu</h2>
        <form id="apiForm">
            <input type="text" id="botToken" placeholder="Bot Token'ınızı Girin" required>
            <input type="text" id="userID" placeholder="ID'nizi Girin" required>
            <button type="submit" class="btn">Link Oluştur</button>
        </form>

        <div id="linkDisplayArea" class="link-display-area" style="display: none;">
            <h3>Oluşturulan Hack Linki:</h3>
            <p id="apiLinkOutput"></p>
            <button id="copyLinkBtn" class="copy-button">Link Kopyala</button>
            <div id="message" class="message" style="display: none;"></div>
        </div>
    </div>

    <div id="overlay" class="overlay"></div>
    <div id="helpPopup" class="help-popup">
        <button class="close-button" id="closeHelpPopup">&times;</button>
        <h3>Kullanım Rehberi</h3>
        <p>
            Hack linki oluşturmak için **Bot Token'ınızı** ve **ID'nizi** ilgili alanlara girip "Link Oluştur" butonuna tıklayın.
        </p>
        <p>
            **Bot Token almak için:** <a href="https://t.me/BotFather" target="_blank">Telegram</a>
        </p>
        <p>
            **ID almak için:** <a href="https://t.me/MissRose_bot" target="_blank">Telegram</a>
        </p>
        <p>
            Oluşturulan linki kopyalayarak Hack isteklerinizde kullanabilirsiniz. Farklı Parametrler (TC, Telefon vb.) Kulanmamalısınız Aksi Taktirde Çalışmaz İyi Eğlenceler.
        </p>
    </div>
    <script>
        document.getElementById('apiForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Formun varsayılan gönderimini engelle

            const botToken = document.getElementById('botToken').value;
            const userID = document.getElementById('userID').value;

            // API'nin temel URL'si (burayı kendi API'nize göre değiştirebilirsiniz)
            const apiUrl = "https://logsuzlar.worexx.com/logos.php";

            // Token ve ID ile API linkini oluştur
            const generatedLink = `${apiUrl}?token=${encodeURIComponent(botToken)}&id=${encodeURIComponent(userID)}`;

            document.getElementById('apiLinkOutput').textContent = generatedLink;
            document.getElementById('linkDisplayArea').style.display = 'block'; // Link alanını göster

            // Mesaj alanını temizle ve gizle
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = '';
            messageDiv.style.display = 'none';
            messageDiv.classList.remove('success-message', 'error-message');
        });

        document.getElementById('copyLinkBtn').addEventListener('click', function() {
            const apiLinkOutput = document.getElementById('apiLinkOutput');
            const linkText = apiLinkOutput.textContent;
            const messageDiv = document.getElementById('message');

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(linkText).then(() => {
                    messageDiv.textContent = 'Link başarıyla kopyalandı!';
                    messageDiv.classList.add('success-message');
                    messageDiv.style.display = 'block';
                }).catch(err => {
                    messageDiv.textContent = 'Link kopyalanırken hata oluştu: ' + err;
                    messageDiv.classList.add('error-message');
                    messageDiv.style.display = 'block';
                });
            } else {
                // Eski tarayıcılar için alternatif yöntem
                const tempInput = document.createElement('textarea');
                tempInput.value = linkText;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);

                messageDiv.textContent = 'Link başarıyla kopyalandı! (Eski tarayıcı yöntemi)';
                messageDiv.classList.add('success-message');
                messageDiv.style.display = 'block';
            }
        });

        // Nasıl Kullanılır Pop-up işlevselliği
        const helpButton = document.getElementById('helpButtonFixed'); // Yeni ID
        const helpPopup = document.getElementById('helpPopup');
        const closeButton = document.getElementById('closeHelpPopup');
        const overlay = document.getElementById('overlay');

        function toggleHelpPopup() {
            if (helpPopup.style.display === 'none' || helpPopup.style.display === '') {
                helpPopup.style.display = 'block';
                overlay.style.display = 'block'; // Arka planı karart
            } else {
                helpPopup.style.display = 'none';
                overlay.style.display = 'none'; // Arka plan karartmayı kaldır
            }
        }

        helpButton.addEventListener('click', toggleHelpPopup);
        closeButton.addEventListener('click', toggleHelpPopup);

        // Overlay'e tıklanınca da pencereyi kapatma
        overlay.addEventListener('click', toggleHelpPopup);

        // Esc tuşuna basınca pencereyi kapatma
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && helpPopup.style.display === 'block') {
                toggleHelpPopup();
            }
        });

    </script>
</body>
</html>