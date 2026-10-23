<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>
    <style>
        /* Genel Sayfa Stili */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        /* Sol Menü (Sidebar) */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: 140px;
            background-color: #1d3b63;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0 20px 20px 0;
        }

        .sidebar img {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .sidebar .divider {
            width: 100%;
            height: 2px;
            background-color: white;
            margin-bottom: 15px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 12px;
            width: 100%;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .sidebar a.active, .sidebar a:hover {
            background-color: #3a5a88;
        }

        /* Üst Başlık */
        .header {
            background-color: #1d3b63;
            color: white;
            padding: 50px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-left: 160px;
            border-radius: 0 0 20px 20px;
        }

      

        /* İçerik Alanı */
        .content {
            margin-left: 180px;
            padding: 40px;
        }

         .container {
            width: 80%;
            max-width: 600px;
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            margin-left: 300px;
        }

        .form-group {
            margin: 15px 0;
        }

        .form-group label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 90%;
            padding: 10px;
            border: 1px solid #000;
            border-radius: 3px;
            font-size: 14px;
        }

        .register-button {
            display: block;
            width: 50%;
            padding: 12px;
            margin-top: 10px;
            background-color: #1d3b63;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
        }

        .register-button:hover {
            background-color: #162c47;
        }
         /* Çıkış Butonu */
         .logout-button {
            display: block;
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 10px;
            width: 100%;
            text-align: center;
            border-radius: 10px;
            background-color:rgb(22, 44, 71);
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background-color:rgb(28, 9, 139);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="#" alt="Üniversite Logosu"> 
        <div class="divider"></div>
        <a href="index.php" class="logout-button"> ANA SAYFA</a>
    </div>

    <div class="header">
   #<br> Hususi/Hizmet Pasaport Başvurusu
    </div>
    
    <div class="container">
        <h2>HESAP OLUŞTURMA</h2>
        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="name">İSİM</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label for="tc">TC Kimlik No</label>
                <input type="text" name="tc" required>
            </div>
            <div class="form-group">
                <label for="email">E-Posta</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Telefon</label>
                <input type="text" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="faculty">Hangi Birimden</label>
                <select name="faculty" required>
                    <option value="">Seçiniz</option>
                    <option value="Mühendislik Fakültesi">Mühendislik Fakültesi</option>
                    <option value="İktisadi ve İdari Bilimler Fakültesi">İktisadi ve İdari Bilimler Fakültesi</option>
                    <option value="Ziraat Fakültesi">Ziraat Fakültesi</option>
                    <option value="Sağlık Bilimleri Fakültesi">Sağlık Bilimleri Fakültesi</option>
                    <option value="Tıp Fakültesi">Tıp Fakültesi</option>
                    <option value="Fen Edebiyat Fakültesi">Fen Edebiyat Fakültesi</option>
                </select>
            </div>
            <button type="submit" class="register-button">KAYIT OL</button>
        </form>
    </div>
</body>
</html>
