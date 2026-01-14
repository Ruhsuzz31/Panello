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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM TC Sorgu | Logsuzlar Stabil System</title>
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
        <h2>GSM ile TC Sorgu</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="gsm" placeholder="GSM (örnek: 5550052661)" value="<?php echo $gsm; ?>" required>
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
                $url = "https://apiv2.tsgonline.net/tsgapis/OrramaKonmaBurragaKoy/gsmtc.php?auth=tsgxyunus&gsm=" . urlencode($gsm);

                $context = stream_context_create([
                    "ssl" => [
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ],
                ]);

                $json = @file_get_contents($url, false, $context);
                if ($json) {
                    $veri = json_decode($json, true);
                    // API yanıtının "success" anahtarını, varlığını ve boş olmadığını kontrol et
                    // $veri["data"]'nın bir dizi olduğunu ve en az bir elemanı olduğunu kontrol et
                    if ($veri && isset($veri["success"]) && $veri["success"] && !empty($veri["data"]) && is_array($veri["data"])) {
                        // Eğer data dizisi boşsa (eleman yoksa) veya ilk eleman dizi değilse başlıkları oluşturamayız.
                        // Bu kontrolü, foreach (array_keys($veri["data"][0])) kısmından önce yapmalıyız.
                        if (count($veri["data"]) > 0 && is_array($veri["data"][0])) {
                            echo "<div class='table-wrapper'><table><thead><tr>";
                            // Başlıkları dinamik olarak oluştur (API'den gelen ilk veri setinin anahtarlarını kullan)
                            foreach (array_keys($veri["data"][0]) as $baslik) {
                                echo "<th>" . htmlspecialchars($baslik) . "</th>";
                            }
                            echo "</tr></thead><tbody>";

                            foreach ($veri["data"] as $satir) {
                                // Her bir satırın bir dizi olduğundan emin olun
                                if (is_array($satir)) { // <-- Bu kontrol eklendi!
                                    echo "<tr>";
                                    foreach ($satir as $deger) {
                                        echo "<td>" . htmlspecialchars($deger ?? '-') . "</td>";
                                    }
                                    echo "</tr>";
                                }
                            }
                            echo "</tbody></table></div>";
                        } else {
                            echo "<p style='margin-top:20px;'>API'den beklenen formatta veri gelmedi veya boş bir veri kümesi döndü.</p>";
                        }
                    } else {
                        echo "<p style='margin-top:20px;'>Veri bulunamadı veya API yanıtı geçersiz.</p>";
                    }
                } else {
                    echo "<p style='margin-top:20px;'>API bağlantı hatası veya boş yanıt.</p>";
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
                // Sadece PHP'den bir çıktı gelmediyse (yani hata veya boş veri durumu yoksa)
                // yükleme animasyonunu göster.
                const resultsContainer = document.getElementById('resultsContainer');
                if (resultsContainer.innerHTML.trim() === '') {
                    document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanını göster
                }

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