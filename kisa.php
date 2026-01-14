<?php
// index.php - Basit Tek Dosya PHP Link Kisaltici (is.gd API)

// ********************** Hata Ayiklama Için Baslangiç **********************
// Bu satirlari geçici olarak ekleyerek tüm PHP hatalarini görmeyi saglayabilirsiniz.
// Canli sunucuda KULLANMAYIN, güvenlik açigi olusturabilir.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ********************** Hata Ayiklama Için Bitis **********************

$shortened_url = '';
$error_message = ''; // Kullaniciya gösterilecek hata mesaji

// Form gönderildi mi kontrol et
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $original_url = trim($_POST['original_url']); // URL'nin basindaki/sonundaki bosluklari temizle

    // URL dogrulama
    if (empty($original_url)) {
        $error_message = "Lütfen bir URL girin.";
    } elseif (!filter_var($original_url, FILTER_VALIDATE_URL)) {
        $error_message = "Lütfen geçerli bir URL formati girin (örn: http://example.com/ ).";
    } else {
        // is.gd API URL'sini olustur
        $is_gd_api_url = "https://is.gd/create.php?format=simple&url=" . urlencode($original_url);

        // cURL ile API istegi gönder
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $is_gd_api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Cevabi string olarak al
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // Baglanti için maksimum 5 saniye bekle
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Veri transferi için maksimum 10 saniye bekle
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Redirectleri takip et
        curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Link Shortener'); // Kullanici aracisini belirt

        $api_response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP durum kodunu al
        $curl_error = curl_error($ch); // cURL hatasini al
        $curl_errno = curl_errno($ch); // cURL hata numarasini al
        curl_close($ch);

        if ($curl_errno) {
            // cURL baglanti veya transfer hatasi
            $error_message = "API baglanti hatasi: " . $curl_error . " (Hata Kodu: " . $curl_errno . "). Sunucunuzun disariya baglanti kurabildiginden ve cURL'ün etkin oldugundan emin olun.";
        } elseif ($http_code != 200) {
            // is.gd API'sinden HTTP 200 disi bir kod geldi (hata)
            $error_message = "is.gd API'sinden hata yaniti alindi. HTTP Kodu: " . $http_code . ". Yanit: " . ($api_response ?: "Yanit yok.");
        } elseif (strpos($api_response, 'Error:') === 0) {
            // is.gd'den gelen spesifik hata mesaji (örn: Error: Please enter a valid URL)
            $error_message = "is.gd API hatasi: " . htmlspecialchars($api_response);
        } else {
            // Basarili yanit
            $shortened_url = trim($api_response);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basit Link Kisaltici</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3;
            --black: #000000;
            --white: #FFFFFF;
            --whiteSmoke: #C4C3CA;
            --darkGray: #1c1c1c;
            --lightGray: #333;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            background-color: var(--black);
            color: var(--white);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            flex-direction: column;
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: var(--mainColor);
            color: var(--white);
            border: 2px solid var(--mainColor);
            padding: 10px 14px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            z-index: 999;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .back-button:hover {
            background-color: var(--white);
            color: var(--mainColor);
            border-color: var(--mainColor);
        }

        .container {
            background-color: var(--darkGray);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            text-align: center;
            width: 100%;
            max-width: 450px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--white);
            font-weight: 700;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 10px;
            border-radius: 6px;
            border: 1px solid var(--lightGray);
            font-size: 16px;
            background-color: #2c2c2c;
            color: var(--white);
        }

        input[type="text"]::placeholder {
            color: var(--whiteSmoke);
        }

        button {
            background-color: var(--mainColor);
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        button:hover {
            background-color: var(--white);
            color: var(--mainColor);
        }

        .result {
            margin-top: 25px;
            background-color: #2c2c2c;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid var(--lightGray);
            word-wrap: break-word;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .result p {
            margin-bottom: 10px;
            color: var(--whiteSmoke);
        }
        .result a {
            color: var(--mainColor);
            text-decoration: none;
            font-weight: bold;
            word-break: break-all;
            margin-bottom: 10px;
        }
        .result a:hover {
            text-decoration: underline;
        }

        .copy-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }

        .copy-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #ff4d4d;
            margin-top: 20px;
            font-weight: bold;
        }

        @media (max-width: 500px) {
            .container {
                margin: 50px 20px;
                padding: 20px;
            }
            h1 {
                font-size: 24px;
                margin-bottom: 20px;
            }
            input[type="text"], button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <a href="javascript:history.back()" class="back-button"><</a>

    <div class="container">
        <h1>Basit Link Kisaltici</h1>
        <form method="POST">
            <input type="text" name="original_url" placeholder="Kisaltmak istediginiz linki yapistirin" required>
            <button type="submit">Kisalt</button>
        </form>
        <?php if ($shortened_url): ?>
            <div class="result">
                <p>Kisaltilmis linkiniz:</p>
                <a href="<?php echo $shortened_url; ?>" target="_blank" id="shortenedLink"><?php echo $shortened_url; ?></a>
                <button class="copy-button" onclick="copyToClipboard()">Kopyala</button>
            </div>
        <?php elseif ($error_message): // Hata mesaji varsa göster ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function copyToClipboard() {
            const shortenedLink = document.getElementById('shortenedLink');
            const range = document.createRange();
            range.selectNode(shortenedLink);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            alert('Kisa link kopyalandi!');
        }
    </script>
</body>
</html>