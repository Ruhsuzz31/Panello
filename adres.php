<?php
session_start();
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: ../giris.php");
    exit();
}

$tc = ""; // Varsayılan olarak boş TC değişkeni tanımla
$sorguYapildi = false; // Sorgu yapılıp yapılmadığını takip eden bir bayrak

// Eğer GET isteği ile 'tc' gelmişse, bu bir sorgudur.
if (!empty($_GET['tc'])) {
    $tc = htmlspecialchars(trim($_GET['tc']));
    $sorguYapildi = true; // Sorgu yapıldı olarak işaretle
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adres Sorgu | Logsuzlar Stabil System</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3;
            --black: #000000;
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Lato', sans-serif;
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
            max-width: 900px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
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

        .table-wrapper {
            overflow-x: auto;
            margin-top: 30px;
            border: 1px solid #333;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        /* Adres sorgusunda anahtar-değer çiftleri için özel tablo stili */
        .table-wrapper table tr th, .table-wrapper table tr td {
            text-align: left; /* Başlıklar ve veriler sola yaslı */
        }
        .table-wrapper table tr th {
            width: 30%; /* Başlık sütununa genişlik vererek dengeyi sağlarız */
            background-color: #111;
            color: var(--mainColor);
        }
        .table-wrapper table tr:nth-child(even) {
            background-color: #1c1c1c;
        }
        .table-wrapper table tr:hover {
            background-color: #2c2c2c;
        }


        /* Loading Spinner and Message CSS */
        .loading-area {
            text-align: center;
            margin-top: 30px;
            display: flex; /* Flexbox ile içerik dikeyde ortalanır */
            flex-direction: column;
            align-items: center; /* Yatayda ortala */
            gap: 15px; /* Elemanlar arası boşluk */
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

            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <a href="../index.php" class="back-button"><</a>

    <div class="container">
        <h2>Adres Sorgulama</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="tc" placeholder="TC Kimlik No" value="<?php echo $tc; ?>" required>
            <button type="submit" class="btn">Sorgula</button>
        </form>

        <div id="loadingArea" class="loading-area" style="display: none;">
            <div class="loading-spinner"></div>
            <p>Sorgulanıyor...</p>
        </div>

        <div id="resultsContainer">
            <?php
            // Sadece bir sorgu yapıldıysa ve TC boş değilse API sorgusunu çalıştır
            if ($sorguYapildi) {
                $url = "https://api.hexnox.pro/sowixapi/adres.php?tc=" . urlencode($tc);

                $context = stream_context_create([
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ],
                ]);

                $json = @file_get_contents($url, false, $context);
                if ($json) {
                    $veri = json_decode($json, true);
                    // "success" anahtarının varlığını ve değerini kontrol et, ayrıca "data"nın boş olmadığını kontrol et
                    if ($veri && isset($veri["success"]) && $veri["success"] && !empty($veri["data"])) {
                        echo "<div class='table-wrapper'><table><tbody>"; // Adres sorgusu genelde tekil veri döndüğü için thead yerine direk tbody başlayabiliriz.
                        foreach ($veri["data"] as $key => $value) {
                            echo "<tr><th>" . htmlspecialchars($key) . "</th><td>" . htmlspecialchars($value ?? '-') . "</td></tr>";
                        }
                        echo "</tbody></table></div>";
                    } else {
                        echo "<p style='margin-top:20px;'>Veri bulunamadı veya API'den beklenen format gelmedi.</p>";
                    }
                } else {
                    echo "<p style='margin-top:20px;'>API bağlantı hatası.</p>";
                }
            } else if (isset($_GET['tc']) && empty($_GET['tc'])) {
                // TC alanı boş gönderildiyse mesaj göster
                echo "<p style='margin-top:20px;'>Lütfen TC Kimlik Numarası girin.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Sayfa yüklendiğinde çalışacak fonksiyon
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            // Eğer URL'de 'tc' parametresi varsa (yani bir sorgu yapılmışsa) ve boş değilse
            if (urlParams.has('tc') && urlParams.get('tc') !== '') {
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