<?php
session_start();
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: ../giris.php");
    exit();
}

// Varsayılan olarak boş değişkenler tanımla
$ad = "";
$soyad = "";
$il = "";
$ilce = "";
$sorguYapildi = false; // Sorgu yapılıp yapılmadığını takip eden bir bayrak

// Eğer GET isteği ile ad ve soyad gelmişse, bu bir sorgudur.
if (!empty($_GET['ad']) && !empty($_GET['soyad'])) {
    $ad = htmlspecialchars(trim($_GET['ad']));
    $soyad = htmlspecialchars(trim($_GET['soyad']));
    $il = htmlspecialchars(trim($_GET['il'] ?? ""));
    $ilce = htmlspecialchars(trim($_GET['ilce'] ?? ""));
    $sorguYapildi = true; // Sorgu yapıldı olarak işaretle
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ad Soyad Sorgu | Logsuzlar Stabil System</title>
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

        .bottom-button {
            margin: 40px auto 0;
            text-align: center;
        }

        .bottom-button a {
            display: inline-block;
            background-color: var(--mainColor);
            color: white;
            padding: 14px 30px;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .bottom-button a:hover {
            background-color: white;
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

            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

    <a href="../index.php" class="back-button"><</a>

    <div class="container">
        <h2>Ad Soyad ile Sorgu</h2>
        <form method="GET" id="queryForm">
            <input type="text" name="ad" placeholder="Ad" value="<?php echo $ad; ?>" required>
            <input type="text" name="soyad" placeholder="Soyad" value="<?php echo $soyad; ?>" required>
            <input type="text" name="il" placeholder="İl (İsteğe Bağlı)" value="<?php echo $il; ?>">
            <input type="text" name="ilce" placeholder="İlçe (İsteğe Bağlı)" value="<?php echo $ilce; ?>">
            <button type="submit" class="btn">Sorgula</button>
        </form>

        <div id="loadingArea" class="loading-area" style="display: none;">
            <div class="loading-spinner"></div>
            <p>Sorgulanıyor...</p>
        </div>

        <div id="resultsContainer">
            <?php
            // Sadece bir sorgu yapıldıysa ve ad/soyad boş değilse veritabanı sorgusunu çalıştır
            if ($sorguYapildi) {
                // Veritabanı bağlantı parametreleri
                $servername = "localhost";
                $username_db = "root"; // Kendi veritabanı kullanıcı adınızı girin
                $password_db = "";     // Kendi veritabanı şifrenizi girin
                $dbname = "101m";      // Kendi veritabanı adınızı girin

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username_db, $password_db);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "SELECT * FROM 101m WHERE ADI = :ad AND SOYADI = :soyad";

                    if (!empty($il)) {
                        $sql .= " AND NUFUSIL = :il";
                    }
                    if (!empty($ilce)) {
                        $sql .= " AND NUFUSILCE = :ilce";
                    }

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':ad', $ad);
                    $stmt->bindParam(':soyad', $soyad);
                    if (!empty($il)) {
                        $stmt->bindParam(':il', $il);
                    }
                    if (!empty($ilce)) {
                        $stmt->bindParam(':ilce', $ilce);
                    }

                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($results) > 0) {
                        echo "<div class='table-wrapper'><table><thead><tr>";
                        foreach (array_keys($results[0]) as $baslik) {
                            echo "<th>" . htmlspecialchars($baslik) . "</th>";
                        }
                        echo "</tr></thead><tbody>";

                        foreach ($results as $satir) {
                            echo "<tr>";
                            foreach ($satir as $deger) {
                                echo "<td>" . htmlspecialchars($deger ?? '') . "</td>";
                            }
                            echo "</tr>";
                        }
                        echo "</tbody></table></div>";
                    } else {
                        echo "<p style='margin-top:20px;'>Veri bulunamadı.</p>";
                    }
                } catch (PDOException $e) {
                    echo "<p style='margin-top:20px;'>Veritabanı hatası: " . $e->getMessage() . "</p>";
                } finally {
                    $conn = null;
                }
            } else if (isset($_GET['ad']) || isset($_GET['soyad']) || isset($_GET['il']) || isset($_GET['ilce'])) {
                // Ad ve Soyad alanları boşsa bu mesajı göster
                echo "<p style='margin-top:20px;'>Lütfen Ad ve Soyad alanlarını doldurunuz.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Sayfa yüklendiğinde çalışacak fonksiyon
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            // Eğer URL'de 'ad' ve 'soyad' parametreleri varsa (yani bir sorgu yapılmışsa)
            if (urlParams.has('ad') && urlParams.has('soyad') && urlParams.get('ad') !== '' && urlParams.get('soyad') !== '') {
                document.getElementById('resultsContainer').style.display = 'none'; // Sonuçları hemen gizle
                document.getElementById('loadingArea').style.display = 'flex'; // Yükleme alanını göster

                // 3 saniye sonra yükleme alanını gizle ve sonuçları göster
                setTimeout(function() {
                    document.getElementById('loadingArea').style.display = 'none';
                    document.getElementById('resultsContainer').style.display = 'block';
                }, 3000); // 3000 milisaniye = 3 saniye
            }
        };

        // Form gönderildiğinde (ama aslında tarayıcıyı PHP'ye yönlendirmeden)
        // input alanlarındaki mevcut değerleri koruyarak.
        // Bu bölüm, form gönderildiğinde animasyonu göstermek için gerekli.
        document.getElementById('queryForm').addEventListener('submit', function(event) {
            // Formun varsayılan gönderimini engelleme, çünkü sayfanın yeniden yüklenmesini istiyoruz
            // event.preventDefault(); // Bu satır kaldırıldı, çünkü PHP'nin çalışması için tam sayfa yenileme gerekiyor.

            // Yükleme animasyonunu ve mesajı göster
            document.getElementById('resultsContainer').style.display = 'none';
            document.getElementById('loadingArea').style.display = 'flex';

            // Burada ekstra bir JavaScript gecikmesi koymuyoruz,
            // çünkü sayfa PHP'ye gönderildiğinde PHP'nin kendi işini yapması bekleniyor.
            // Sayfa yüklendiğinde 'window.onload' devreye girecek.
        });
    </script>
</body>
</html>