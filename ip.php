<?php
session_start();
// Kullanıcı girişi kontrolü
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: ../giris.php"); // Giriş yapılmadıysa giriş sayfasına yönlendir
    exit();
}

$ip_adresi = ""; // IP adresi girişini tutacak değişken (Türkçeleştirildi)
$sorgu_yapildi = false; // Bir sorgunun yapılıp yapılmadığını gösteren bayrak (Türkçeleştirildi)

// Eğer URL'de 'ip' parametresi varsa ve boş değilse
if (!empty($_GET['ip'])) {
    $ip_adresi = htmlspecialchars(trim($_GET['ip'])); // IP adresini al ve temizle
    $sorgu_yapildi = true; // Sorgu yapıldı olarak işaretle
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Sorgu | Logsuzlar Stabil System</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* CSS Değişkenleri */
        :root {
            --mainColor: #2196f3;
            --black: #000000;
            --white: #FFFFFF;
            --whiteSmoke: #C4C3CA;
        }

        /* Temel CSS Sıfırlaması */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Lato', sans-serif;
            margin: 0;
            background-color: var(--black);
            color: var(--white);
        }

        /* Geri Butonu */
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

        /* Konteyner */
        .container {
            max-width: 900px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }

        /* Başlıklar */
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: var(--white);
        }

        /* Form Stili */
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

        /* Tablo Stili */
        .table-wrapper {
            overflow-x: auto; /* Yatay kaydırma çubuğu */
            margin-top: 30px;
            border: 1px solid #333;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px; /* Küçük ekranlarda tablonun küçülmesini engelle */
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
            position: sticky; /* Başlıkların kaydırmada sabit kalmasını sağlar */
            top: 0;
            z-index: 1;
        }

        tr:nth-child(even) {
            background-color: #1c1c1c; /* Tek/çift satır renklendirmesi */
        }

        tr:hover {
            background-color: #2c2c2c; /* Satır üzerine gelince renk değişimi */
        }

        /* Yükleme Alanı (Spinner) */
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

        /* Hata Mesajı */
        .error-message {
            color: #ff6347; /* Domates rengi */
            margin-top: 20px;
            font-weight: bold;
            text-align: center;
        }

        /* Duyarlı Tasarım */
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
        <h2>IP Sorgu</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="ip" placeholder="IP Adresi (örn: 8.8.8.8)" value="<?php echo htmlspecialchars($ip_adresi); ?>" required pattern="^((25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$" title="Geçerli bir IPv4 adresi girin (örn: 8.8.8.8)">
            <button type="submit" class="btn">Sorgula</button>
        </form>

        <div id="loadingArea" class="loading-area" style="display: none;">
            <div class="loading-spinner"></div>
            <p>Sorgulanıyor...</p>
        </div>

        <div id="resultsContainer">
            <?php
            // Sadece bir sorgu yapıldıysa ve IP boş değilse API sorgusunu çalıştır
            if ($sorgu_yapildi) {
                $hata_mesaji = null; // Hata mesajı için değişkeni sıfırla (Türkçeleştirildi)

                // API URL'si
                $api_url = "http://ip-api.com/json/" . urlencode($ip_adresi); // Değişken adı Türkçeleştirildi

                // cURL ile API isteği
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_url); // Değişken adı Türkçeleştirildi
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Yanıtı bir string olarak döndür
                // SSL hatası almamak için (bu API http olduğu için belki gerekmez ama genel pratik)
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $json_yanit = curl_exec($ch); // API yanıtını al (JSON stringi) (Türkçeleştirildi)
                $http_kodu = curl_getinfo($ch, CURLINFO_HTTP_CODE); // HTTP durum kodunu al (Türkçeleştirildi)
                $curl_hata = curl_error($ch); // cURL hata mesajını al (Türkçeleştirildi)
                curl_close($ch);
                
                $api_veri = json_decode(trim($json_yanit), true); // JSON'ı PHP dizisine çevir (Türkçeleştirildi)

                // API'den gelen yanıtı kontrol et
                if ($http_kodu === 200 && !empty($json_yanit)) {
                    if ($api_veri && is_array($api_veri) && !empty($api_veri)) {
                        // API'den gelen 'status' değerini kontrol edelim (başarılı mı?)
                        if (isset($api_veri['status']) && $api_veri['status'] === 'success') {
                            echo "<div class='table-wrapper'><table><thead><tr>";
                            // Başlıkları dinamik olarak oluştur
                            foreach (array_keys($api_veri) as $baslik) {
                                // 'query' başlığını 'Sorgulanan IP' olarak değiştir, alt çizgileri kaldır
                                echo "<th>" . htmlspecialchars(str_replace(['_', 'query'], [' ', 'Sorgulanan IP'], ucfirst($baslik))) . "</th>";
                            }
                            echo "</tr></thead><tbody><tr>";
                            // Verileri tek satır olarak yazdır
                            echo "<tr>"; 
                            foreach ($api_veri as $anahtar => $deger) { // Değişken adları Türkçeleştirildi
                                // Enlem (lat) ve Boylam (lon) değerlerini 2 ondalık basamağa yuvarla
                                if ($anahtar === 'lat' || $anahtar === 'lon') {
                                    $gosterilecek_deger = round($deger, 2); // (Türkçeleştirildi)
                                } else {
                                    // Null değerleri boş string yap ve HTML özel karakterlerini dönüştür
                                    $gosterilecek_deger = htmlspecialchars($deger ?? ''); // (Türkçeleştirildi)
                                }
                                echo "<td>" . $gosterilecek_deger . "</td>";
                            }
                            echo "</tr>";
                            echo "</tbody></table></div>";
                        } else {
                            // API status'u success değilse (örn. "fail")
                            $hata_mesaji = "Sorgulama başarısız oldu. API Mesajı: " . htmlspecialchars($api_veri['message'] ?? 'Bilinmeyen Hata');
                        }
                    } else {
                        // JSON çözümlenemedi veya boş/beklenmeyen bir format geldi
                        $hata_mesaji = "API'den beklenen format gelmedi veya boş veri döndü.";
                    }
                } else {
                    // cURL bağlantı hatası veya HTTP kodu 200 değil
                    $hata_mesaji = "API bağlantı hatası veya sunucu hatası (HTTP Kodu: " . htmlspecialchars($http_kodu) . ").";
                    if (!empty($curl_hata)) {
                         $hata_mesaji .= " cURL Hatası: " . htmlspecialchars($curl_hata) . ".";
                    }
                }
                
                // Hata mesajı varsa göster
                if ($hata_mesaji) {
                    echo "<p class='error-message'>" . $hata_mesaji . "</p>";
                }
            } else if (isset($_GET['ip']) && empty($_GET['ip'])) {
                // IP alanı boş gönderildiyse mesaj göster
                echo "<p class='error-message'>Lütfen bir IP Adresi girin.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Sayfa yüklendiğinde çalışacak fonksiyon
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            // Eğer URL'de 'ip' parametresi varsa ve boş değilse
            if (urlParams.has('ip') && urlParams.get('ip') !== '') {
                const resultsContainer = document.getElementById('resultsContainer');
                // Hata mesajı div'i varsa veya debug bilgisi varsa spinner'ı gösterme
                const hasErrorOrDebug = resultsContainer.querySelector('.error-message') || resultsContainer.querySelector('.debug-info');
                
                if (!hasErrorOrDebug && (resultsContainer.innerHTML.trim() === '' || resultsContainer.querySelector('table') === null)) {
                     document.getElementById('resultsContainer').style.display = 'none'; // Sonuçları gizle
                     document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanını göster

                     // 3 saniye sonra yükleme alanını gizle ve sonuçları göster
                     setTimeout(function() {
                         document.getElementById('loadingArea').style.display = 'none';
                         document.getElementById('resultsContainer').style.display = 'block';
                     }, 3000); // 3000 milisaniye = 3 saniye
                }
            }
        };

        // Form gönderildiğinde
        document.getElementById('queryForm').addEventListener('submit', function(event) {
            // Sadece form geçerliyse (HTML5 pattern validation geçerliyse) spinner'ı göster
            if (this.checkValidity()) {
                // Yükleme animasyonunu ve mesajı göster
                document.getElementById('resultsContainer').style.display = 'none';
                document.getElementById('loadingArea').style.display = 'flex';
            }
        });
    </script>
</body>
</html>