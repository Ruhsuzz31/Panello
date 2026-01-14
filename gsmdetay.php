<?php
session_start();
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: ../giris.php");
    exit();
}

$gsm = ""; // Varsayılan olarak boş GSM değişkeni tanımla
$sorguYapildi = false; // Sorgu yapılıp yapılmadığını takip eden bir bayrak

// Eğer GET isteği ile 'gsm' gelmişse, bu bir sorgudur.
if (!empty($_GET['gsm'])) {
    $gsm = htmlspecialchars(trim($_GET['gsm']));
    $sorguYapildi = true; // Sorgu yapıldı olarak işaretle
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GSM Detay | Logsuzlar Stabil System</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3;
            --black: #000;
            --white: #fff;
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
            background-color: white;
            color: var(--mainColor);
        }

        .container {
            max-width: 1000px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--white);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        input {
            padding: 12px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
            background-color: #333;
            color: white;
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
        }

        .btn:hover {
            background-color: white;
            color: var(--mainColor);
        }

        .result {
            margin-top: 30px;
            background-color: #1c1c1c;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #444;
        }

        .result table {
            width: 100%;
            border-collapse: collapse;
        }

        .result th, .result td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        .result th {
            background-color: #111;
            color: var(--mainColor);
        }

        /* Loading Spinner and Message CSS */
        .loading-area {
            text-align: center;
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top: 4px solid var(--mainColor);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            input, .btn {
                font-size: 14px;
            }

            .result th, .result td {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<a href="../index.php" class="back-button"><</a>

<div class="container">
    <h2>GSM Detay Sorgulama</h2>
    <form method="GET" id="queryForm">
        <input type="text" name="gsm" placeholder="GSM Numarası (5xx...)" value="<?php echo $gsm; ?>" required pattern="^5[0-9]{9}$" title="Geçerli bir 10 haneli GSM numarası girin (örn: 5551234567)">
        <button type="submit" class="btn">Sorgula</button>
    </form>

    <div id="loadingArea" class="loading-area" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Sorgulanıyor...</p>
    </div>

    <div id="resultsContainer">
        <?php
        // Sadece bir sorgu yapıldıysa ve GSM boş değilse API sorgusunu çalıştır
        if ($sorguYapildi) {
            $url = "https://api.hexnox.pro/sowixapi/gsmdetay.php?gsm=" . urlencode($gsm);

            $context = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ]);

            $json = @file_get_contents($url, false, $context);
            if ($json) {
                $veri = json_decode($json, true);
                // API yanıtının "success" anahtarını ve "Data" anahtarının varlığını ve boş olmadığını kontrol et
                if ($veri && isset($veri["success"]) && $veri["success"] === true && isset($veri["Data"])) {
                    echo "<div class='result'><table>";
                    echo "<thead><tr><th>Alan</th><th>Bilgi</th></tr></thead><tbody>";
                    foreach ($veri["Data"] as $key => $value) {
                        $displayKey = htmlspecialchars(ucfirst(strtolower($key))); // Keys like 'TC', 'AD', etc. will be displayed as 'Tc', 'Ad'
                        // Special handling for some keys for better display
                        switch ($displayKey) {
                            case 'Tc': $displayKey = 'T.C. Kimlik No'; break;
                            case 'Ad': $displayKey = 'Adı'; break;
                            case 'Soyad': $displayKey = 'Soyadı'; break;
                            case 'Dogumtarihi': $displayKey = 'Doğum Tarihi'; break;
                            case 'Adresil': $displayKey = 'İl'; break;
                            case 'Adresilce': $displayKey = 'İlçe'; break;
                            case 'Anneadi': $displayKey = 'Anne Adı'; break;
                            case 'Annetc': $displayKey = 'Anne T.C.'; break;
                            case 'Babaadi': $displayKey = 'Baba Adı'; break;
                            case 'Babatc': $displayKey = 'Baba T.C.'; break;
                            case 'Cinsiyet': $displayKey = 'Cinsiyet'; break;
                            case 'Verginumarasi': $displayKey = 'Vergi Numarası'; break;
                            case 'Ikametgah': $displayKey = 'İkametgah Adresi'; break;
                        }
                        $value = htmlspecialchars($value ?? '-'); // Null değerler için hata vermemesi için ?? '-' eklendi
                        echo "<tr><td>$displayKey</td><td>$value</td></tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p style='margin-top:20px;'>Geçerli veri bulunamadı veya API yanıtı hatalı.</p>";
                }
            } else {
                echo "<p style='margin-top:20px;'>API bağlantı hatası oluştu.</p>";
            }
        } else if (isset($_GET['gsm']) && empty($_GET['gsm'])) {
            // GSM alanı boş gönderildiyse mesaj göster
            echo "<p style='margin-top:20px;'>Lütfen GSM Numarası girin.</p>";
        }
        ?>
    </div>
</div>

<script>
    // Sayfa yüklendiğinde çalışacak fonksiyon
    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        // Eğer URL'de 'gsm' parametresi varsa (yani bir sorgu yapılmışsa) ve boş değilse
        if (urlParams.has('gsm') && urlParams.get('gsm') !== '') {
            document.getElementById('resultsContainer').style.display = 'none'; // Sonuçları hemen gizle
            document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanını göster

            // 3 saniye sonra yükleme alanını gizle ve sonuçları göster
            setTimeout(function() {
                document.getElementById('loadingArea').style.display = 'none';
                document.getElementById('resultsContainer').style.display = 'block';
            }, 3000); // 3000 milisaniye = 3 saniye
        }
    };

    // Form gönderildiğinde (ancak tarayıcıyı PHP'ye yönlendirmeden)
    document.getElementById('queryForm').addEventListener('submit', function(event) {
        // Yükleme animasyonunu ve mesajı göster
        document.getElementById('resultsContainer').style.display = 'none';
        document.getElementById('loadingArea').style.display = 'flex';

        // Buraya ekstra bir JavaScript gecikmesi koymuyoruz,
        // çünkü sayfa PHP'ye gönderildiğinde PHP'nin kendi işini yapması bekleniyor.
        // Sayfa yüklendiğinde 'window.onload' devreye girecek ve gecikmeyi o yönetecek.
    });
</script>
</body>
</html>