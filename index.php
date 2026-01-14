<?php
session_start();
if (!isset($_SESSION['giris']) || $_SESSION['giris'] !== true) {
    header("Location: giris.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Logsuzlar Stabil System | PROJE</title>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --mainColor: #2196f3; /* Orijinal Mavi */
            --black: #000000;
            --white: #FFFFFF;
            --whiteSmoke: #C4C3CA;
            --lightGray: #333333;
            --darkGray: #1a1a1a;
        }
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
            -webkit-font-smoothing: antialiased; /* Better font rendering */
            -moz-osx-font-smoothing: grayscale; /* Better font rendering */
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
        .text-center {
            text-align: center;
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
            box-shadow: 0 2px 10px rgba(0,0,0,0.5); /* Added subtle shadow */
        }
        nav .logo {
            color: var(--white);
            font-size: 28px;
            font-weight: 600;
            text-transform: capitalize;
            letter-spacing: 0.5px; /* Added letter spacing for logo */
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
            white-space: nowrap; /* Prevent text wrapping */
        }
        nav .logout:hover {
            background-color: var(--white);
            color: var(--mainColor);
            transform: translateY(-1px); /* Subtle lift on hover */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2); /* Enhanced hover shadow */
        }

        /* Genel Buton Stilleri */
        .btn {
            height: 40px;
            padding: 0 25px;
            background-color: var(--mainColor);
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            color: var(--white);
            border: none;
            cursor: pointer;
            transition: all .2s ease;
            width: 100%;
            max-width: 350px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            letter-spacing: 0.5px; /* Added letter spacing to buttons */
        }
        .btn:hover {
            background-color: var(--white);
            color: var(--mainColor);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        /* Buton Listesi - Bu bölüm artık sidebar tarafından yönetilecek */
        .button-list {
            display: none; /* Hide desktop-only button list, now handled by sidebar */
            flex-direction: column;
            align-items: center;
            gap: 12px;
            margin-top: 50px;
            width: 100%;
            max-width: 400px;
        }

        /* Yeni Eklenen Alanlar */
        .hero-section {
            padding: 40px 0;
            text-align: center;
            margin-bottom: 30px;
            max-width: 800px;
        }
        .hero-section h1 {
            font-size: 38px;
            color: var(--mainColor);
            margin-bottom: 15px;
            font-weight: 900;
            letter-spacing: 1px; /* Added letter spacing for main heading */
        }
        .hero-section p {
            font-size: 17px;
            color: var(--whiteSmoke);
            line-height: 1.8;
            margin-bottom: 25px;
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 40px;
            width: 100%;
            max-width: 1000px;
            justify-content: center; /* Center grid items horizontally */
        }
        .feature-card {
            background-color: var(--darkGray);
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            border: 1px solid var(--lightGray);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.6);
        }
        .feature-card h4 {
            font-size: 18px;
            color: var(--mainColor);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .feature-card p {
            font-size: 14px;
            color: var(--whiteSmoke);
        }

        /* FAQ ve İletişim */
        .faq-item {
            padding: 15px 0;
            border-top: 1px solid var(--lightGray);
            text-align: center;
            margin-top: 50px;
            max-width: 700px;
            width: 100%; /* Ensure it takes full width within container */
        }
        .faq-item h3 {
            font-size: 20px;
            color: var(--mainColor);
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .faq-item p {
            font-size: 14px;
            color: var(--whiteSmoke);
            line-height: 1.5;
        }
        .contact-link {
            margin-top: 30px;
            text-align: center;
            width: 100%;
            max-width: 350px;
        }

        /* Hamburger Menü Stilleri */
        .hamburger-icon {
            display: none; /* Hidden by default, shown on mobile */
            width: 25px;
            height: 18px;
            position: relative;
            cursor: pointer;
            z-index: 1000; /* Increased z-index to ensure visibility */
        }
        .hamburger-icon span {
            display: block;
            width: 100%;
            height: 2px;
            background-color: var(--white); /* Ensure the lines are white */
            position: absolute;
            left: 0;
            transition: all 0.3s ease;
        }
        .hamburger-icon span:nth-child(1) { top: 0; }
        .hamburger-icon span:nth-child(2) { top: 50%; transform: translateY(-50%); }
        .hamburger-icon span:nth-child(3) { top: 100%; transform: translateY(-100%); }

        /* Animation for X icon */
        .hamburger-icon.open span:nth-child(1) { top: 50%; transform: translateY(-50%) rotate(45deg); }
        .hamburger-icon.open span:nth-child(2) { opacity: 0; }
        .hamburger-icon.open span:nth-child(3) { top: 50%; transform: translateY(-50%) rotate(-45deg); }

        .sidebar {
            height: 100%;
            width: 280px; /* Default width for desktop */
            position: fixed;
            z-index: 998;
            top: 0;
            left: 0;
            background-color: var(--black);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 70px;
            box-shadow: 2px 0 15px rgba(0,0,0,0.6);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .sidebar a {
            padding: 12px 20px;
            font-size: 16px;
            color: var(--whiteSmoke);
            width: 90%;
            border-radius: 4px;
            background-color: var(--lightGray);
            text-align: center;
            transition: all 0.2s ease; /* Added transition for sidebar links */
        }
        .sidebar a:hover {
            color: var(--mainColor);
            background-color: var(--white);
            transform: translateX(5px); /* Slight movement on hover */
        }

        /* Adjust main content when sidebar is open on desktop */
        body.sidebar-open main {
            margin-left: 280px; /* Space for the sidebar */
        }

        /* Hack Bölümü notu ve buton stili */
        .birthday-section {
            margin-top: 40px;
            padding: 30px;
            background-color: var(--darkGray);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            border: 1px solid var(--lightGray);
            text-align: center;
            max-width: 600px;
            width: 100%;
            display: flex; /* Changed to flex for better alignment */
            flex-direction: column;
            align-items: center;
        }
        .birthday-section h3 {
            font-size: 24px;
            color: var(--mainColor);
            margin-bottom: 15px;
            font-weight: 800;
            letter-spacing: 0.7px;
        }
        .birthday-section p {
            font-size: 16px;
            color: var(--whiteSmoke);
            line-height: 1.7;
            margin-bottom: 25px;
            max-width: 500px; /* Constrain paragraph width */
        }
        .birthday-section .btn {
            margin-top: 15px;
            max-width: 250px;
        }

        /* Chat Sistemi Bölümü */
        .chat-section {
            margin-top: 50px;
            padding: 40px;
            background-color: var(--darkGray);
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            border: 1px solid var(--lightGray);
            text-align: center;
            max-width: 700px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .chat-section h3 {
            font-size: 24px;
            color: var(--mainColor);
            margin-bottom: 10px;
            font-weight: 800;
            letter-spacing: 0.7px;
        }
        .chat-section p {
            font-size: 16px;
            color: var(--whiteSmoke);
            line-height: 1.7;
            margin-bottom: 15px;
            max-width: 550px;
        }
        .chat-section .btn {
            margin-top: 20px;
            max-width: 300px;
            height: 45px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            background-color: var(--mainColor);
            color: var(--white);
            border: none;
        }
        .chat-section .btn:hover {
            background-color: var(--white);
            color: var(--mainColor);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }

        /* Responsive Ayarlamalar */
        @media (max-width: 768px) {
            nav {
                padding: 15px 20px;
            }
            nav .container {
                background-color: var(--black);
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
            h2.text-center {
                font-size: 24px;
            }
            .hamburger-icon {
                display: block; /* Show hamburger on mobile */
            }
            .sidebar {
                width: 0; /* Hidden by default on mobile */
                box-shadow: none; /* No shadow when hidden */
            }
            .sidebar.open {
                width: 65vw; /* Open width on mobile */
                box-shadow: 2px 0 15px rgba(0,0,0,0.6); /* Shadow when open */
            }
            .desktop-only {
                display: none !important; /* Hide desktop-specific button list */
            }
            .button-list {
                display: none; /* Ensure this is hidden on mobile */
            }
            .btn {
                max-width: 300px;
                height: 38px;
                font-size: 13px;
            }
            .faq-item {
                margin-top: 40px;
                padding: 15px;
            }
            .faq-item h3 {
                font-size: 18px;
            }
            .faq-item p {
                font-size: 13px;
            }
            .contact-link {
                max-width: 300px;
            }
            .hero-section h1 {
                font-size: 30px;
            }
            .hero-section p {
                font-size: 15px;
            }
            .features-grid {
                grid-template-columns: 1fr; /* Mobil için tek sütun */
                padding: 0 15px; /* Mobil padding */
            }
            .birthday-section {
                padding: 20px;
            }
            .birthday-section h3 {
                font-size: 20px;
            }
            .birthday-section p {
                font-size: 14px;
            }
            .chat-section { /* Mobil için chat bölümü ayarları */
                padding: 20px;
            }
            .chat-section h3 {
                font-size: 20px;
            }
            .chat-section p {
                font-size: 14px;
            }
            .chat-section .btn {
                max-width: 250px;
                height: 40px;
                font-size: 13px;
            }
        }

        @media (min-width: 769px) {
            .hamburger-icon {
                display: none; /* Hide hamburger on desktop */
            }
            .sidebar {
                width: 250px; /* Always show sidebar on desktop */
            }
            nav .logo {
                margin-left: 250px; /* Offset logo to account for sidebar */
            }
            .full-screen {
                margin-left: 250px; /* Push main content to the right */
                padding-left: 0;
                padding-right: 0;
            }
            .container {
                max-width: calc(1080px - 250px); /* Adjust container width */
                margin: auto; /* Center the container in the remaining space */
            }
            .button-list {
                display: none !important; /* Ensure desktop-only buttons remain hidden as sidebar is now main navigation */
            }
        }
    </style>
</head>
<body>

    <nav>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <div class="hamburger-icon" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="logo">Logsuz<span>lar</span></div>
            <a href="cikis.php" class="logout">Çıkış Yap</a>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <a href="pages/adsoyad.php" class="btn sidebar-btn">Ad Soyad Sorgu</a>
        <a href="pages/tc.php" class="btn sidebar-btn">TC Sorgu</a>
        <a href="pages/gsmtc.php" class="btn sidebar-btn">GSM-TC Sorgu</a>
        <a href="pages/tcgsm.php" class="btn sidebar-btn">TC-GSM Sorgu</a>
        <a href="pages/aile.php" class="btn sidebar-btn">Aile Sorgu</a>
        <a href="pages/sulale.php" class="btn sidebar-btn">Sülale Sorgu</a>
        <a href="pages/gsmdetay.php" class="btn sidebar-btn">Gsm Detay Sorgu</a>
        <a href="pages/isyeri.php" class="btn sidebar-btn">İşyeri Sorgu</a>
        <a href="pages/smsb.php" class="btn sidebar-btn">Sms Bomb</a>
        <a href="pages/gpt.php" class="btn sidebar-btn">Logsuz GPT</a>
        <a href="pages/ip.php" class="btn sidebar-btn">İp Sorgu</a>
        <a href="pages/adres.php" class="btn sidebar-btn">Adres Sorgu</a>
        <a href="pages/vesika.php" class="btn sidebar-btn">Vesika Sorgu</a>
        <a href="pages/serino.php" class="btn sidebar-btn">Seri No Sorgu</a>
        <a href="pages/kisa.php" class="btn sidebar-btn">Link Kısaltıcı</a>
        <a href="pages/logos.php" class="btn sidebar-btn">Hack Bölümü</a>
    </div>

    <section class="full-screen">
        <div class="container">
            <div class="hero-section">
                <h1>Logsuzlar Stabil System'e Hoş Geldiniz!</h1>
                <p>Türkiye'nin en kapsamlı ve güvenilir veri sorgulama platformu olan Logsuzlar Stabil System ile ihtiyaç duyduğunuz bilgilere anında ve güvenle ulaşın. Amacımız, hızlı, doğru ve ücretsiz hizmet sunarak bilgiye erişiminizi kolaylaştırmaktır.</p>
                <p>**Unutmayın:** Tüm hizmetlerimiz **tamamen ücretsizdir** ve herkesin kullanımına açıktır. Ücretli satış yapan kişilere itibar etmeyiniz. Herhangi bir dolandırıcılık durumunda sorumluluk kabul edilmemektedir.</p>
            </div>

            <div class="birthday-section">
                <h3>Hack Bölümümüze Gelmeye Ne Dersin ?</h3>
                <p>
                    VIP üye olabilmek için sitemizin premium üyelik seçeneklerini inceleyin. Üyelik avantajları arasında daha hızlı sorgulama, özel hizmetler ve canlı konum & ig Çalma bilgileri bulunmaktadır.
                </p>
                <a href="pages/hack.php" class="btn">Gitmek İçin Buraya Tıklayın</a>
            </div>

            <h2 class="text-center" style="margin-bottom: 25px;">Başlıca Hizmetlerimiz</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <h4>Kapsamlı Sorgulama</h4>
                    <p>Ad soyad, TC kimlik numarası, GSM ve daha birçok kritere göre detaylı sorgulamalar yapın.</p>
                </div>
                <div class="feature-card">
                    <h4>Hızlı ve Güvenilir</h4>
                    <p>Anlık sonuçlarla zaman kaybetmeden doğru bilgilere erişin. Verileriniz bizimle güvende.</p>
                </div>
                <div class="feature-card">
                    <h4>Kullanıcı Dostu Arayüz</h4>
                    <p>Basit ve sezgisel arayüzümüz sayesinde istediğiniz sorguyu kolayca gerçekleştirin.</p>
                </div>
                <div class="feature-card">
                    <h4>Geniş Veritabanı</h4>
                    <p>Sülale, aile, işyeri gibi farklı kategorilerde zengin veri tabanımızdan faydalanın.</p>
                </div>
            </div>

            <div class="chat-section">
                <h3>Anlık Destek ve Sohbet İçin Bize Katılın!</h3>
                <p>
                    Sorularınız mı var? Yardım mı arıyorsunuz? Veya sadece topluluğumuzla sohbet etmek mi istiyorsunuz?
                    Canlı chat sistemimiz, uzman ekibimizle anında iletişime geçmenizi ve diğer kullanıcılarla etkileşim kurmanızı sağlar.
                    Hızlı çözümler, samimi sohbetler ve yeni arkadaşlar edinmek için hemen katılın!
                </p>
                <a href="pages/chat.php" class="btn">Sohbete Başla!</a>
            </div>

            <div class="faq-item" style="border-top: 1px solid var(--lightGray); margin-top: 70px;">
                <h3>Sıkça Sorulan Sorular</h3>
                <p>
                    **Logsuzlar Stabil System nedir?**<br>
                    Logsuzlar Stabil System, kullanıcılara çeşitli veri sorgulama hizmetlerini ücretsiz sunan bir platformdur. Amacımız, bilgiye erişimi kolay ve güvenli hale getirmektir.
                </p>
                <p style="margin-top: 15px;">
                    **Hizmetleriniz gerçekten ücretsiz mi?**<br>
                    Evet, tüm hizmetlerimiz tamamen ücretsizdir ve herhangi bir ücret talep edilmez.
                </p>
            </div>

            <div class="contact-link">
                <a href="https://t.me/Yucemiz" target="_blank" class="btn">Yardım ve İletişim</a>
            </div>
        </div>
    </section>

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const body = document.body; // Reference to the body element

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            hamburger.classList.toggle('open');
            body.classList.toggle('sidebar-open'); // Toggle class on body for main content adjustment
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (event) => {
            if (sidebar.classList.contains('open') &&
                !sidebar.contains(event.target) &&
                !hamburger.contains(event.target)) {
                sidebar.classList.remove('open');
                hamburger.classList.remove('open');
                body.classList.remove('sidebar-open');
            }
        });

        // Close sidebar when a sidebar link is clicked (for better mobile UX)
        document.querySelectorAll('.sidebar-btn').forEach(button => {
            button.addEventListener('click', () => {
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    hamburger.classList.remove('open');
                    body.classList.remove('sidebar-open');
                }
            });
        });

        // Ensure sidebar is always open on desktop
        function checkSidebarDisplay() {
            if (window.innerWidth > 768) {
                sidebar.classList.add('open'); // Ensure sidebar is open
                body.classList.add('sidebar-open'); // Apply margin to body
            } else {
                sidebar.classList.remove('open'); // Close sidebar on mobile initially
                body.classList.remove('sidebar-open'); // Remove margin on body
            }
        }

        // Run on load and resize
        window.addEventListener('load', checkSidebarDisplay);
        window.addEventListener('resize', checkSidebarDisplay);
    </script>

</body>
</html>