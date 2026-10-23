<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasaport Başvurusu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('#');
            background-size: cover;
            background-position: center;
            margin: 0;
            
        }

        .container {
    text-align: center;
    background-color: rgba(255, 255, 255, 0.9); /* Hafif şeffaf beyaz */
    border: none; /* Daha modern bir görünüm için kenarlık kaldırıldı */
    padding: 40px; /* Paddingi artırarak daha geniş alan */
    padding-top: 100px; /* Üst paddingi düşürdük */
    border-radius: 15px; /* Daha yuvarlak kenarlar */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Daha yumuşak ve derin bir gölge efekti */
    width: 380px; /* Genişliği biraz artırdık */
    max-width: 90%; /* Ekran boyutuna göre daha esnek */
    margin: 0 auto; /* Ortalamak için */
    position: relative;
    overflow: hidden;
     /* Sola kaydırma işlemi */
    transition: transform 0.3s ease-in-out; /* Animasyon ekledik */
}

/* Hover efektiyle modern bir etkileşim */
.container:hover {
    transform: translateX(0); /* Hover durumunda sola kaydırmayı sıfırlıyoruz */
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3); /* Hoverda gölgeyi biraz daha belirgin yapıyoruz */
}
.logo {
    width: 120px; /* Logonun genişliği */
    height: auto; /* Orantılı boyutlandırma */
    margin-bottom: 20px; /* Logo ile içerik arasında boşluk */
    border-radius: 50%; /* Yuvarlatılmış logo köşeleri (isteğe bağlı) */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Logo etrafında hafif gölge */
    
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animasyon */
}



        .header {
            background-color: #1d3b63;
            color: white;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            width: calc(100% + 4px);
            position: absolute;
            top: -2px;
            left: -2px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            box-sizing: border-box;
        }

        .button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 80%;
            padding: 12px;
            margin: 10px auto;
            background-color: #2d5186;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #233e66;
        }

        .text {
            margin-top: 10px;
            font-size: 12px;
        }

        .text a {
            color: #2d5186;
            text-decoration: none;
            font-weight: bold;
        }

        .text a:hover {
            text-decoration: underline;
        }

        .forgot-password {
            margin-top: 10px;
            font-size: 12px;
        }

        .forgot-password a {
            color: #d9534f;
            text-decoration: none;
            font-weight: bold;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
    <img src="#" alt="Logo" class="logo">
        <div class="header"># <br> PASAPORT BAŞVURUSU</div>
        
        <form action="giris.php" method="POST">
            <input type="text" name="email" placeholder="E-Posta" required style="width: 80%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
            <input type="password" name="password" placeholder="Şifre" required style="width: 80%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px;">
            <button type="submit" class="button">
                <i class="fa-solid fa-sign-in-alt"></i> GİRİŞ YAP
            </button>
        </form>

        <div class="forgot-password">
            <a href="sifre.php">Şifremi Unuttum</a>
        </div>

        <div class="text">Hesabınız yok mu?</div>
        <button class="button" onclick="window.location.href='kayit.php'">
            <i class="fa-solid fa-user-plus"></i> KAYIT OL
        </button>
    </div>
</body>

</html>
