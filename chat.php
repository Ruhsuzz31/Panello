<?php
session_start(); // Oturumu başlat

// Kullanıcı girişi kontrolü ve isim isteme
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: giris.php"); // Eğer giriş yapılmamışsa giris.php'ye yönlendir
    exit();
}

// Kullanıcı adı oturumda ayarlanmamışsa, isim sorma formunu göster
if (!isset($_SESSION['kullanici_adi'])) {
    if (isset($_POST['set_username']) && !empty($_POST['username'])) {
        $_SESSION['kullanici_adi'] = htmlspecialchars(trim($_POST['username']));
        header("Location: chat.php"); // İsim ayarlandıktan sonra kendini yenile
        exit();
    }
    // İsim sorma formu HTML'i (Sadece ilk giriş için)
    ?>
    <!DOCTYPE html>
    <html lang="tr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>İsim Girin</title>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
        <style>
            :root {
                --mainColor: #2196f3;
                --black: #000000;
                --white: #FFFFFF;
                --whiteSmoke: #C4C3CA;
                --lightGray: #333333;
                --darkGray: #1a1a1a;
            }
            body {
                font-family: 'Lato', sans-serif;
                background-color: var(--black);
                color: var(--white);
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .username-form-container {
                background-color: var(--darkGray);
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.5);
                border: 1px solid var(--lightGray);
                text-align: center;
                max-width: 400px;
                width: 90%;
            }
            .username-form-container h2 {
                color: var(--mainColor);
                margin-bottom: 25px;
                font-size: 28px;
            }
            .username-form-container input[type="text"] {
                width: calc(100% - 20px);
                padding: 12px 10px;
                margin-bottom: 20px;
                border: 1px solid var(--lightGray);
                border-radius: 6px;
                background-color: var(--black);
                color: var(--white);
                font-size: 16px;
            }
            .username-form-container button {
                width: 100%;
                padding: 12px 20px;
                background-color: var(--mainColor);
                color: var(--white);
                border: none;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s ease;
            }
            .username-form-container button:hover {
                background-color: var(--white);
                color: var(--mainColor);
            }
        </style>
    </head>
    <body>
        <div class="username-form-container">
            <h2>Sohbete Katılın</h2>
            <form method="POST">
                <input type="text" name="username" placeholder="Lütfen bir isim girin" required>
                <button type="submit" name="set_username">Sohbete Başla</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit(); // İsim girilmesini beklerken kodun geri kalanını çalıştırma
}

// Kullanıcı adı oturumdan alındı, yoksa 'Misafir' olarak ayarla
$username = $_SESSION['kullanici_adi'];

// Mesajların saklanacağı dosya yolu (Bu dosya nerede olursa olsun, 'data' klasörünü proje kökünde arar)
$messages_file = __DIR__ . '/../data/messages.json';

// 'data' klasörünün mevcut olup olmadığını kontrol et, yoksa oluştur
if (!is_dir(dirname($messages_file))) {
    mkdir(dirname($messages_file), 0777, true); // Klasörü oluştur, 0777 izinleri ile
}

// Mesaj gönderme işlemi
if (isset($_POST['action']) && $_POST['action'] === 'send_message') {
    $message = $_POST['message'] ?? '';

    if (!empty($message)) {
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); // Mesajı temizle

        $current_messages = [];
        if (file_exists($messages_file)) {
            $current_messages = json_decode(file_get_contents($messages_file), true);
            if (!is_array($current_messages)) {
                $current_messages = [];
            }
        }

        $new_message = [
            'username' => $username,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $current_messages[] = $new_message;

        // Son 100 mesajı tut
        $current_messages = array_slice($current_messages, -100);

        file_put_contents($messages_file, json_encode($current_messages, JSON_PRETTY_PRINT));
    }
    // AJAX isteğine yanıt olarak boş bir çıktı veya durum döndürebiliriz
    echo json_encode(['status' => 'success']);
    exit(); // AJAX isteğinden sonra çık
}

// Mesajları alma işlemi (AJAX isteğiyle)
if (isset($_GET['action']) && $_GET['action'] === 'get_messages') {
    $messages = [];
    if (file_exists($messages_file)) {
        $messages = json_decode(file_get_contents($messages_file), true);
        if (!is_array($messages)) {
            $messages = [];
        }
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit(); // AJAX isteğinden sonra çık
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../favicon.png"> <title>Logsuzlar Stabil System | Sohbet</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        /* CSS değişkenleri */
        :root {
            --mainColor: #2196f3;
            --black: #000000;
            --white: #FFFFFF;
            --whiteSmoke: #C4C3CA;
            --lightGray: #333333;
            --darkGray: #1a1a1a;
            --chatBubbleUser: #007bff; /* Mavi (kullanıcı mesajları) */
            --chatBubbleOther: #555; /* Koyu gri (diğer mesajlar) */
        }
        /* Genel CSS kuralları */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Lato', sans-serif;
            font-size: 15px;
            background-color: var(--black);
            color: var(--white);
            line-height: 1.6;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        .container {
            max-width: 1080px;
            margin: auto;
            padding: 0 20px;
        }
        .full-screen {
            min-height: 100vh;
            padding: 140px 0 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Navigasyon Çubuğu */
        nav {
            width: 100%;
            background-color: var(--black);
            position: fixed;
            z-index: 999;
            padding: 18px 25px;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--lightGray);
        }
        nav .logo {
            color: var(--white);
            font-size: 28px;
            font-weight: 600;
            text-transform: capitalize;
        }
        nav .logo span {
            color: var(--mainColor);
        }
        nav .logout {
            font-size: 13px;
            background-color: var(--mainColor);
            color: var(--white);
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        nav .logout:hover {
            background-color: var(--white);
            color: var(--mainColor);
        }

        /* Updated styles for the back button */
        .back-button {
            display: flex;
            align-items: center;
            justify-content: center; /* Center content horizontally */
            width: 40px; /* Adjust width as needed for a button shape */
            height: 40px; /* Adjust height as needed */
            background-color: var(--mainColor); /* Blue background */
            color: var(--white); /* White text/icon */
            border-radius: 50%; /* Make it round */
            font-size: 0; /* Hide text if only icon is desired, or adjust for text */
            transition: background-color 0.2s ease, color 0.2s ease;
            text-decoration: none; /* Remove underline */
        }

        /* Style for the SVG icon within the button */
        .back-button svg {
            margin-right: 0; /* Remove margin if text is hidden or centered */
            width: 24px; /* Ensure icon size */
            height: 24px; /* Ensure icon size */
            stroke: var(--white); /* White stroke for the arrow */
        }

        .back-button:hover {
            background-color: var(--white);
            color: var(--mainColor); /* Text/icon color changes on hover */
        }

        .back-button:hover svg {
            stroke: var(--mainColor); /* SVG color changes on hover */
        }

        /* Sohbet Sistemi Özel Stilleri */
        .chat-container {
            background-color: var(--darkGray);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            border: 1px solid var(--lightGray);
            width: 100%;
            max-width: 700px;
            height: 70vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header {
            background-color: var(--mainColor);
            color: var(--white);
            padding: 15px 20px;
            border-top-left-radius: 9px;
            border-top-right-radius: 9px;
            font-size: 20px;
            font-weight: 700;
            text-align: center;
        }
        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .chat-message {
            max-width: 80%;
            padding: 10px 15px;
            border-radius: 8px;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .chat-message .username {
            font-size: 12px;
            color: var(--whiteSmoke);
            margin-bottom: 5px;
            display: block;
        }
        .chat-message.self {
            align-self: flex-end;
            background-color: var(--chatBubbleUser);
            color: var(--white);
            border-bottom-right-radius: 0;
        }
        .chat-message.other {
            align-self: flex-start;
            background-color: var(--chatBubbleOther);
            color: var(--white);
            border-bottom-left-radius: 0;
        }
        .chat-input {
            padding: 15px 20px;
            border-top: 1px solid var(--lightGray);
            display: flex;
            gap: 10px;
        }
        .chat-input input[type="text"] {
            flex-grow: 1;
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid var(--lightGray);
            background-color: var(--black);
            color: var(--white);
            font-size: 15px;
            outline: none;
        }
        .chat-input button {
            background-color: var(--mainColor);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .chat-input button:hover {
            background-color: var(--white);
            color: var(--mainColor);
        }

        /* Responsive Ayarlamalar */
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            nav .logo {
                font-size: 24px;
            }
            nav .logout {
                font-size: 12px;
                padding: 7px 14px;
            }
            .full-screen {
                padding: 100px 0 30px;
            }
            .chat-container {
                height: 80vh;
                max-width: 95%;
            }
            .chat-input {
                flex-direction: column;
            }
            .chat-input input[type="text"],
            .chat-input button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <nav>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <a href="../index.php" class="back-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                </a>
            <a href="../index.php" class="logo">Logsuz<span>lar</span></a>
            <a href="../cikis.php" class="logout">Çıkış Yap</a>
        </div>
    </nav>

    <section class="full-screen">
        <div class="container">
            <div class="chat-container">
                <div class="chat-header">
                    Logsuzlar Sohbet (Merhaba, <?php echo $username; ?>!)
                </div>
                <div class="chat-messages" id="chat-messages">
                </div>
                <div class="chat-input">
                    <input type="text" id="message-input" placeholder="Mesajınızı buraya yazın...">
                    <button id="send-button">Gönder</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('message-input');
        const sendButton = document.getElementById('send-button');
        const currentUser = "<?php echo $username; ?>";

        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Mesajları çeken fonksiyon
        async function fetchMessages() {
            try {
                // Kendi dosyasından (chat.php) mesajları getiriyor
                const response = await fetch('chat.php?action=get_messages');
                const messages = await response.json();
                chatMessages.innerHTML = '';
                messages.forEach(msg => {
                    const messageElement = document.createElement('div');
                    messageElement.classList.add('chat-message');
                    if (msg.username === currentUser) {
                        messageElement.classList.add('self');
                    } else {
                        messageElement.classList.add('other');
                    }
                    messageElement.innerHTML = `<span class="username">${msg.username} (${msg.timestamp}):</span> ${msg.message}`;
                    chatMessages.appendChild(messageElement);
                });
                scrollToBottom();
            } catch (error) {
                console.error('Mesajlar çekilirken hata oluştu:', error);
            }
        }

        // Mesaj gönderme fonksiyonu
        async function sendMessage() {
            const message = messageInput.value.trim();
            if (message === '') return;

            try {
                const response = await fetch('chat.php', { // Kendi dosyasına (chat.php) gönderiyor
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=send_message&message=${encodeURIComponent(message)}`
                });

                const result = await response.json();
                if (result.status === 'success') {
                    messageInput.value = '';
                    fetchMessages();
                } else {
                    console.error('Mesaj gönderilirken hata oluştu:', result.message);
                    alert('Mesaj gönderilirken bir hata oluştu: ' + result.message);
                }
            } catch (error) {
                console.error('Mesaj gönderilirken ağ hatası oluştu:', error);
                alert('Mesaj gönderilirken bir ağ hatası oluştu.');
            }
        }

        // Olay dinleyicileri
        sendButton.addEventListener('click', sendMessage);
        messageInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                sendMessage();
            }
        });

        // İlk yüklemede ve sonra her 3 saniyede bir mesajları çek
        fetchMessages();
        setInterval(fetchMessages, 3000);
    </script>

</body>
</html>