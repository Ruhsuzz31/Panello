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
    <title>Vesika Sorgu | Logsuzlar Stabil System</title>
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

        .table-wrapper {
            overflow-x: auto;
            margin-top: 30px;
            border: 1px solid #333;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Geniş tablolar için */
        }

        th, td {
            padding: 8px 12px;
            border-bottom: 1px solid #444;
            text-align: left;
            font-size: 14px;
            white-space: nowrap; /* İçeriğin tek satırda kalmasını sağlar */
        }

        th {
            background-color: #111;
            color: var(--mainColor);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #1c1c1c;
        }

        tr:hover {
            background-color: #2c2c2c;
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

        /* Vesika resmi için stil */
        td img.vesika-resim {
            max-width: 100px; /* Resmin boyutunu sınırlar */
            height: auto;
            border-radius: 4px;
            display: block; /* Resmi ortalamak için */
            margin: 0 auto;
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
        <h2>Vesika Sorgu</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="tc" placeholder="TC Kimlik No" value="<?php echo $tc; ?>" required pattern="\d{11}" title="11 haneli TC girin">
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
                // API URL'si ve bağlam ayarları
                // Buradaki API URL'sini kendi vesika API'nizin URL'si ile değiştirin
                $url = "https://quantrexsystems.alwaysdata.net/diger/ozel/vesika/jessyvesika.php?tc=" . urlencode($tc);

                $context = stream_context_create([
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ],
                ]);

                $json = @file_get_contents($url, false, $context);
                
                if ($json) {
                    $veri = json_decode($json, true);
                    
                    // API çıktısı doğrudan veri nesnesi ise (yani "data" anahtarı yoksa)
                    if ($veri && is_array($veri) && !empty($veri)) {
                        echo "<div class='table-wrapper'><table><thead><tr>";
                        // Başlıkları dinamik olarak oluştur
                        foreach (array_keys($veri) as $baslik) {
                            echo "<th>" . htmlspecialchars(ucfirst($baslik)) . "</th>"; // Başlıkları büyük harfle başlatmak için ucfirst eklendi
                        }
                        echo "</tr></thead><tbody><tr>";
                        // Verileri tek satır olarak yazdır
                        foreach ($veri as $anahtar => $deger) {
                            echo "<td>";
                            // Eğer anahtar 'vesika' ise, Base64 verisini <img> etiketi olarak göster
                            if ($anahtar === 'vesika' && !empty($deger)) {
                                echo "<img src=\"data:image/jpeg;base64," . htmlspecialchars(trim($deger)) . "\" alt=\"Vesika\" class=\"vesika-resim\">";
                            } else {
                                echo htmlspecialchars($deger ?? '');
                            }
                            echo "</td>";
                        }
                        echo "</tr></tbody></table></div>";
                    } else {
                        // API'den gelen JSON çözümlenemedi veya boş/beklenmeyen bir format geldi
                        echo "<p style='margin-top:20px;'>Veri bulunamadı veya API'den beklenen format gelmedi.</p>";
                    }
                } else {
                    // file_get_contents bir hata döndürdüyse (örn. bağlantı kurulamadı)
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
                // Sonuçlar boşsa veya bir tablo içermiyorsa loading spinner'ı göster
                const resultsContainer = document.getElementById('resultsContainer');
                if (resultsContainer.innerHTML.trim() === '' || resultsContainer.querySelector('table')) {
                     document.getElementById('resultsContainer').style.display = 'none'; // Sonuçları hemen gizle
                     document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanını göster

                     // 3 saniye sonra yükleme alanını gizle ve sonuçları göster
                     setTimeout(function() {
                         document.getElementById('loadingArea').style.display = 'none';
                         document.getElementById('resultsContainer').style.display = 'block';
                     }, 3000); // 3000 milisaniye = 3 saniye
                }
            }
        };

        // Form gönderildiğinde (ama aslında tarayıcıyı PHP'ye yönlendirmeden)
        document.getElementById('queryForm').addEventListener('submit', function(event) {
            // Sadece form geçerliyse (HTML5 pattern validation geçerliyse) spinner'ı göster
            if (this.checkValidity()) {
                // Yükleme animasyonunu ve mesajı göster
                document.getElementById('resultsContainer').style.display = 'none';
                document.getElementById('loadingArea').style.display = 'flex';
            }
            // Sayfa PHP'ye gönderildiği için, buraya ek bir gecikme koymaya gerek yok.
            // Sayfa yüklendiğinde 'window.onload' fonksiyonu gecikmeyi otomatik olarak işleyecek.
        });
    </script>
</body>
</html>