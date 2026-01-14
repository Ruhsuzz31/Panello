<?php
session_start();
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: ../giris.php");
    exit();
}

$tc = ""; // Varsayilan olarak bos TC degiskeni tanimla
$sorguYapildi = false; // Sorgu yapilip yapilmadigini takip eden bir bayrak

// Eger GET istegi ile 'tc' gelmisse, bu bir sorgudur.
if (!empty($_GET['tc'])) {
    $tc = htmlspecialchars(trim($_GET['tc']));
    $sorguYapildi = true; // Sorgu yapildi olarak isaretle
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seri No Sorgu | Logsuzlar Stabil System</title>
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
            min-width: 800px;
        }

        th, td {
            padding: 8px 12px;
            border-bottom: 1px solid #444;
            text-align: left;
            font-size: 14px;
            white-space: nowrap;
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
        <h2>Seri No Sorgu</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="tc" placeholder="TC Kimlik No" value="<?php echo $tc; ?>" required pattern="\d{11}" title="11 haneli TC girin">
            <button type="submit" class="btn">Sorgula</button>
        </form>

        <div id="loadingArea" class="loading-area" style="display: none;">
            <div class="loading-spinner"></div>
            <p>Sorgulaniyor...</p>
        </div>

        <div id="resultsContainer">
            <?php
            // Sadece bir sorgu yapildiysa ve TC bos degilse API sorgusunu çalistir
            if ($sorguYapildi) {
                // API URL'si ve baglam ayarlari
                $url = "https://quantrexsystems.alwaysdata.net/diger/serino.php?tc=" . urlencode($tc);

                $context = stream_context_create([
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ],
                ]);

                $json = @file_get_contents($url, false, $context);
                
                if ($json) {
                    $veri = json_decode($json, true);
                    // API çiktisi dogrudan veri nesnesi ise (yani "data" anahtari yoksa)
                    if ($veri && is_array($veri) && !empty($veri)) {
                        echo "<div class='table-wrapper'><table><thead><tr>";
                        // Basliklari dinamik olarak olustur
                        foreach (array_keys($veri) as $baslik) {
                            echo "<th>" . htmlspecialchars($baslik) . "</th>";
                        }
                        echo "</tr></thead><tbody><tr>";
                        // Verileri tek satir olarak yazdir
                        foreach ($veri as $deger) {
                            echo "<td>" . htmlspecialchars($deger ?? '') . "</td>";
                        }
                        echo "</tr></tbody></table></div>";
                    } else {
                        // API'den gelen JSON çözümlenemedi veya bos/beklenmeyen bir format geldi
                        echo "<p style='margin-top:20px;'>Veri bulunamadi veya API'den beklenen format gelmedi.</p>";
                    }
                } else {
                    // file_get_contents bir hata döndürdüyse (örn. baglanti kurulamadi)
                    echo "<p style='margin-top:20px;'>API baglanti hatasi.</p>";
                }
            } else if (isset($_GET['tc']) && empty($_GET['tc'])) {
                // TC alani bos gönderildiyse mesaj göster
                echo "<p style='margin-top:20px;'>Lütfen TC Kimlik Numarasi girin.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Sayfa yüklendiginde çalisacak fonksiyon
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            // Eger URL'de 'tc' parametresi varsa (yani bir sorgu yapilmissa) ve bos degilse
            if (urlParams.has('tc') && urlParams.get('tc') !== '') {
                // Sonuçlar bossa veya bir tablo içermiyorsa loading spinner'i göster
                const resultsContainer = document.getElementById('resultsContainer');
                if (resultsContainer.innerHTML.trim() === '' || resultsContainer.querySelector('table')) {
                     document.getElementById('resultsContainer').style.display = 'none'; // Sonuçlari hemen gizle
                     document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanini göster

                     // 3 saniye sonra yükleme alanini gizle ve sonuçlari göster
                     setTimeout(function() {
                         document.getElementById('loadingArea').style.display = 'none';
                         document.getElementById('resultsContainer').style.display = 'block';
                     }, 3000); // 3000 milisaniye = 3 saniye
                }
            }
        };

        // Form gönderildiginde (ama aslinda tarayiciyi PHP'ye yönlendirmeden)
        document.getElementById('queryForm').addEventListener('submit', function(event) {
            // Sadece form geçerliyse (HTML5 pattern validation geçerliyse) spinner'i göster
            if (this.checkValidity()) {
                // Yükleme animasyonunu ve mesaji göster
                document.getElementById('resultsContainer').style.display = 'none';
                document.getElementById('loadingArea').style.display = 'flex';
            }
            // Sayfa PHP'ye gönderildigi için, buraya ek bir gecikme koymaya gerek yok.
            // Sayfa yüklendiginde 'window.onload' fonksiyonu gecikmeyi otomatik olarak isleyecek.
        });
    </script>
</body>
</html>