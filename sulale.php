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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sülale Sorgu | Logsuzlar Stabil System</title>
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

        h3 {
            margin-top: 40px;
            margin-bottom: 20px;
            color: var(--mainColor);
            text-align: center;
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
            min-width: 1000px;
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

        .info-section {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #444;
            border-radius: 6px;
            background-color: #1c1c1c;
        }

        .info-section p {
            margin: 5px 0;
            font-size: 15px;
        }
        .info-section p strong {
            color: var(--mainColor);
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
    <h2>Sülale Bilgisi Sorgu</h2>
    <form method="GET" id="queryForm">
        <input type="text" name="tc" placeholder="TC Kimlik Numarası" value="<?php echo $tc; ?>" required>
        <button type="submit" class="btn">Sorgula</button>
    </form>

    <div id="loadingArea" class="loading-area" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Sorgulanıyor...</p>
    </div>

    <div id="resultsContainer">
        <?php
        // Yardımcı fonksiyon: Veriyi tablo olarak göstermek için
        function displayTable($data, $title) {
            if (!empty($data) && is_array($data)) {
                echo "<h3>" . htmlspecialchars($title) . "</h3>";
                echo "<div class='table-wrapper'><table><thead><tr>";
                // Tablo başlıklarını ilk dizinin anahtarlarından dinamik olarak oluştur
                foreach (array_keys($data[0]) as $header) {
                    echo "<th>" . htmlspecialchars($header) . "</th>";
                }
                echo "</tr></thead><tbody>";
                // Her bir satırı tabloya ekle
                foreach ($data as $row) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . htmlspecialchars($value ?? '-') . "</td>"; // null değerleri '-' olarak göster
                    }
                    echo "</tr>";
                }
                echo "</tbody></table></div>";
            }
        }

        // Eğer sorgu yapıldıysa
        if ($sorguYapildi) {
            // API URL'si
            $url = "https://apiv2.tsgonline.net/tsgapis/OrramaKonmaBurragaKoy/sulale.php?auth=tsgxyunus&tc=" . urlencode($tc);

            // SSL sertifikası doğrulamayı kapatan bağlam (sadece test/geliştirme için kullanılmalı!)
            $context = stream_context_create([
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ]);

            // API'den JSON verisini çek
            $json = @file_get_contents($url, false, $context);
            if ($json) {
                $veri = json_decode($json, true); // JSON'ı PHP dizisine dönüştür

                // API başarıyla yanıt verdiyse ve veri varsa
                if ($veri && isset($veri["success"]) && $veri["success"] === "true") {

                    // Genel Bilgiler bölümünü göster (Yapımcı ve Telegram hariç)
                    if (isset($veri["info"]) && is_array($veri["info"])) {
                        echo "<h3>Genel Bilgiler</h3>";
                        echo "<div class='info-section'>";
                        foreach ($veri["info"] as $key => $value) {
                            // "Telegram" ve "Yapımcı" anahtarlarını atla
                            if ($key !== "Telegram" && $key !== "Yapımcı") {
                                echo "<p><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</p>";
                            }
                        }
                        echo "</div>";
                    }

                    // Gösterilecek sülale bilgisi bölümlerinin sıralaması ve başlıkları
                    $sections = [
                        "kendisi" => "Kişisel Bilgiler",
                        "annesi" => "Anne Bilgileri",
                        "babası" => "Baba Bilgileri",
                        "kardesler" => "Kardeş Bilgileri",
                        "cocuklar" => "Çocuk Bilgileri",
                        "anne_tarafi_kuzenler" => "Anne Tarafı Kuzenler",
                        "baba_tarafi_kardesler" => "Baba Tarafı Kardeşler",
                        "baba_tarafi_kuzenler" => "Baba Tarafı Kuzenler"
                    ];

                    // Her bir bölümü döngüyle kontrol et ve tablo olarak göster
                    foreach ($sections as $key => $title) {
                        if (isset($veri[$key]) && is_array($veri[$key])) {
                            displayTable($veri[$key], $title);
                        }
                    }

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