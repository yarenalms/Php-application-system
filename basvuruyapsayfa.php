<?php
session_start();
include 'db.php';

// Kullanıcı giriş yapmış mı kontrol et
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başvuru Yap</title>
    <style>
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

        /* Hesap Sahibi Adı */
        .account-name {
            position: absolute;
            right: 20px;
            top: 70px;
            background-color: white;
            color: black;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        /* İçerik Alanı */
        .content {
            margin-left: 180px;
            padding: 40px;
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
            background-color: #d9534f;
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c9302c;
        }
        /* Sayfa düzeni */
        

        .form-container {
            border: 2px solid #1d3b63;
            padding: 30px;
            border-radius: 10px;
            background-color: #f9f9f9;
            width: 50%;
            /* Form genişliği */
            margin: 40px auto;
            /* ÜSTTEN BOŞLUK EKLENDİ */
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }


        /* Form içindeki tüm elemanları düzenli yerleştir */
        .form-container form {
            width: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Form grupları için düzen */
        .form-container .form-group {
            width: 100%;
            margin-bottom: 15px;
            /* Elemanlar arasına boşluk */
        }

        /* Label (Başlıkları sola hizala) */
        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            text-align: left;
            display: block;
        }

        /* Form input ve select stilleri */
        .form-container input,
        .form-container select {
            width: 100%;
            /* Kutu içine tam otursun */
            padding: 10px;
            border: 1px solid black;
            border-radius: 5px;
            font-size: 14px;
            text-align: left;
            /* Yazılar sola hizalansın */
        }

        /* Butonu ortala */
        .submit-button {
            width: 100%;
            padding: 12px;
            background-color: #1d3b63;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .submit-button:hover {
            background-color: #162c47;
        }

        
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="# " alt="Üniversite Logosu">
        <div class="divider"></div>
        <a href="kullanicisayfa.php"> BAŞVURULARIM</a>
        <a href="#" class="active"> BAŞVURU YAP</a>
        <a href="index.php" class="logout-button">ÇIKIŞ</a>
    </div>

    <div class="header">
        # <br> Hususi/Hizmet Pasaport Başvurusu
        <div class="account-name"><?php echo htmlspecialchars($user_name); ?></div>
    </div>

    <div class="form-container">
        <h3>BAŞVURU YAP</h3>
        <form action="submit_application.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>PASAPORT TÜRÜ</label>
                <select name="pasaport_turu" required>
                    <option value="">Pasaport Türü Seçiniz</option>
                    <option value="Hususi">Hususi Pasaport</option>
                    <option value="Hizmet">Hizmet Pasaportu</option>
                </select>
            </div>
            <div class="form-group">
    <label for="kadro">Kadro Derecesi</label>
    <select name="kadro" required>
        <option value="">Kadro Derecesi Seçiniz</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
        <option value="10">11</option>
        <option value="10">12</option>
    </select>
</div>
<div class="form-group">
<label for="unvan">Ünvan</label>
    <input type="text" name="unvan" placeholder="ÜNVAN" required>
</div>
<div class="form-group">
<label for="kurum_sicil_no">Kurum sicil no </label>
    <input type="text" name="kurum_sicil_no" placeholder="KURUM SİCİL NO" required>
</div>
<div class="form-group">
<label for="tel_no">Tel no</label>
    <input type="text" name="tel_no" placeholder="TEL NO" required>
</div>
<div class="form-group">
<label for="email">E posta </label>
    <input type="email" name="email" placeholder="E POSTA" required>
</div>
<div class="form-group">
    <label for="nufus_mudurlugu">Nüfus Müdürlüğü</label>
    <select name="nufus_mudurlugu" required>
        <option value="">Nüfus Müdürlüğü Seçiniz</option>
        <option value="Adana">Adana</option>
        <option value="Ankara">Ankara</option>
        <option value="Antalya">Antalya</option>
        <option value="Bursa">Bursa</option>
        <option value="Diyarbakır">Diyarbakır</option>
        <option value="Erzurum">Erzurum</option>
        <option value="Eskişehir">Eskişehir</option>
        <option value="Gaziantep">Gaziantep</option>
        <option value="İstanbul">İstanbul</option>
        <option value="İzmir">İzmir</option>
        <option value="Kayseri">Kayseri</option>
        <option value="Kocaeli">Kocaeli</option>
        <option value="Konya">Konya</option>
        <option value="Malatya">Malatya</option>
        <option value="Mersin">Mersin</option>
        <option value="Samsun">Samsun</option>
        <option value="Sivas">Sivas</option>
        <option value="Trabzon">Trabzon</option>
        <option value="Şanlıurfa">Şanlıurfa</option>
        <option value="Van">Van</option>
    </select>
</div>


            <div class="form-group">
                <label>Eşiniz var mı?</label>
                <select name="esi_var_mi" id="esi_var_mi" onchange="toggleEsFile()">
                    <option value="Yok">Yok</option>
                    <option value="Var">Var</option>
                </select>
            </div>

            <div class="form-group" id="esKimlikFotokopisi" style="display: none;">
                <label>Eşinizin Kimlik Fotokopisini Yükleyin</label>
                <input type="file" name="es_kimlik_fotokopisi">
            </div>

            <div class="form-group">
                <label>Çocuk Sayısı</label>
                <select name="cocuk_sayisi" id="cocuk_sayisi" onchange="toggleCocukFiles()">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

            <div id="cocukFotokopiContainer"></div>

            <div class="form-group">
                <label>KİMLİK FOTOKOPİNİZİ YÜKLEYİNİZ</label>
                <input type="file" name="kimlik_fotokopisi" required>
            </div>
            <button type="submit" class="submit-button">BAŞVUR</button>
        </form>
    </div>

    <script>
        function toggleEsFile() {
            var esSecim = document.getElementById("esi_var_mi").value;
            var esFotokopi = document.getElementById("esKimlikFotokopisi");

            if (esSecim === "Var") {
                esFotokopi.style.display = "block"; // Eş kimlik alanını göster
            } else {
                esFotokopi.style.display = "none"; // Eş kimlik alanını gizle
            }
        }

        function toggleCocukFiles() {
            var cocukSayisi = document.getElementById("cocuk_sayisi").value;
            var container = document.getElementById("cocukFotokopiContainer");

            // Önce mevcut içerikleri temizle
            container.innerHTML = "";

            // Eğer çocuk sayısı 0'dan büyükse dosya yükleme alanları oluştur
            if (cocukSayisi > 0) {
                for (var i = 1; i <= cocukSayisi; i++) {
                    var div = document.createElement("div");
                    div.className = "form-group";
                    div.innerHTML = `
                    <label>Çocuk ${i} Kimlik Fotokopisi</label>
                    <input type="file" name="cocuk_kimlik_fotokopisi_${i}" required>
                `;
                    container.appendChild(div);
                }
            }
        }
    </script>


</body>

</html>