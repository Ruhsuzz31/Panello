<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_message = urlencode(htmlspecialchars($_POST["message"]));

    // API URL'sini oluşturma
    $api_url = "http://ramowlf.xyz/ramowlf/gpt.php?msg=" . $user_message;

    // API'ye GET isteği gönderme
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    // API'den dönen cevabı işleme
    $response_data = json_decode($response, true);
    $api_reply = isset($response_data['reply']) ? $response_data['reply'] : 'API cevap veremedi.';

    // Oturumda cevapları saklama
    $_SESSION['messages'][] = [
        'user_message' => $_POST["message"],
        'api_reply' => $api_reply
    ];
}

// Geçmiş mesajları alma
$messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sohbet Uygulaması | Logsuzlar Stabil System</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3; /* The blue color from the TC Sorgu page */
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
            background-color: var(--black); /* Set background to black */
            color: var(--white);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* YENİ '<' ŞEKLİNDEKİ GERİ BUTONU STİLLERİ - BU SAYFAYA ÖZEL EKLEME */
        .top-left-back-button {
            position: fixed; /* Ekranın belirli bir noktasında kalır */
            top: 15px; /* Üstten 15px boşluk */
            left: 15px; /* Soldan 15px boşluk */
            z-index: 100000; /* Diğer elementlerin üzerinde görünmesini sağlar */
        }

        .top-left-back-button button {
            background-color: var(--mainColor); /* BUTONUN ARKA PLANI MAVİ */
            color: var(--white); /* BUTONUN İÇİNDEKİ '<' SİMGESİ BEYAZ */
            border: none;
            padding: 5px 8px;
            border-radius: 8px;
            font-size: 24px;
            cursor: pointer;
            font-weight: 700;
            line-height: 1;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 35px;
            width: 35px;
        }

        .top-left-back-button button:hover {
            background-color: #1976D2; /* mainColor'ın biraz daha koyu tonu */
        }
        /* YENİ '<' ŞEKLİNDEKİ GERİ BUTONU STİLLERİ SONU */

        .chat-container {
            background-color: transparent; /* Remove background, let body handle it */
            padding: 20px;
            border-radius: 10px;
            width: 400px;
            /* Removed box-shadow, as it wasn't in the desired style */
        }

        .chat-box {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 10px;
            background-color: #1c1c1c; /* Background for chat messages area, darker grey */
            height: 200px;
            overflow-y: auto;
            border: 1px solid #333; /* Added a subtle border */
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 6px; /* Slightly less rounded corners */
            background-color: #2c2c2c; /* User message background */
            color: #ffffff;
            font-size: 14px; /* Adjusted font size */
        }

        .chat-reply {
            margin-top: 10px;
            margin-bottom: 10px; /* Added margin-bottom for spacing between replies */
            padding: 10px;
            border-radius: 6px; /* Slightly less rounded corners */
            background-color: var(--mainColor); /* API reply background, using --mainColor (blue) */
            color: var(--white); /* White text for API reply */
            font-weight: normal; /* Normal font weight */
            font-size: 14px; /* Adjusted font size */
        }

        .chat-input {
            width: 100%; /* Full width */
            padding: 12px; /* Increased padding */
            border-radius: 6px; /* Rounded corners */
            border: none;
            font-size: 16px;
            background-color: #333; /* Dark background for inputs */
            color: white;
            margin-bottom: 15px; /* Spacing below input */
        }

        .chat-input::placeholder {
            color: #aaa; /* Placeholder color */
        }

        .chat-button { /* .back-button kaldırıldı */
            width: 100%;
            padding: 12px; /* Increased padding */
            border-radius: 6px; /* Rounded corners */
            border: none;
            background-color: var(--mainColor); /* Blue background for buttons */
            color: white;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s; /* Smooth transition */
            text-align: center;
            text-decoration: none;
            display: block;
        }

        .chat-button:hover { /* .back-button:hover kaldırıldı */
            background-color: white; /* White background on hover */
            color: var(--mainColor); /* Blue text on hover */
        }

        /* Eski .back-button stilleri kaldırıldı */
        /* .back-button {
            margin-top: 15px;
        } */

        @media (max-width: 600px) {
            .chat-container {
                width: 90%;
            }

            .chat-input, .chat-button { /* .back-button kaldırıldı */
                font-size: 14px;
            }

            .chat-message, .chat-reply {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="top-left-back-button">
        <button onclick="history.back()">&lt;</button>
    </div>
    <div class="chat-container">
        <div class="chat-box" id="chat-box"> <?php foreach ($messages as $message): ?>
                <div class="chat-message"><strong>Ben:</strong> <?php echo htmlspecialchars($message['user_message']); ?></div>
                <div class="chat-reply"><?php echo htmlspecialchars($message['api_reply']); ?></div>
            <?php endforeach; ?>
        </div>
        <form action="" method="POST">
            <input class="chat-input" type="text" name="message" placeholder="Mesajınızı yazın..." required>
            <button class="chat-button" type="submit">Gönder</button>
        </form>
    </div>

    <script>
        // Sayfa yüklendiğinde ve yeni bir mesaj eklendiğinde sohbet kutusunu aşağı kaydır
        document.addEventListener('DOMContentLoaded', function() {
            var chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
    </script>
</body>
</html>