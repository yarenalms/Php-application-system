<?php
session_start();
include 'db.php';

// Kullanıcı giriş yapmış mı kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 0) {
    header("Location: index.php");
    exit();
}
$kullanici_adi = $_SESSION['user_name'];
$kullanici_id = $_SESSION['user_id'];

// Kullanıcının başvurusunu çek
$sql = "SELECT surec, notlar FROM applications WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();
$basvuru = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Sayfası</title>
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

        /* Başvuru Kartları */
        .application-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 400px;
            padding: 15px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            transition: all 0.3s ease;
        }

        .application-box:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Detay Butonu */
        .details-button {
            background-color: #1d3b63;
            color: white;
            border: none;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .details-button:hover {
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
            background-color: #d9534f;
            margin-top: 10px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-button:hover {
            background-color: #c9302c;
        }
        
        /* Başvuruların genel konteyneri */
        .application-container {
            width: 100%;
            max-width: 600px;
            /* Daha dar bir alan kaplaması için */
            margin: 20px auto;
            /* Ortalamak için */
            text-align: center;
        }

       
        /* Başvuru durumu */
        .status {
            font-weight: bold;
            color:rgb(167, 15, 15);
        }
     
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="#" alt="Üniversite Logosu">
        <div class="divider"></div>
        <a href="#" class="active">BAŞVURULARIM</a>
        <a href="basvuruyapsayfa.php">BAŞVURU YAP</a>
        <a href="index.php" class="logout-button">ÇIKIŞ</a>
    </div>

    <div class="header">
        #<br> Hususi/Hizmet Pasaport Başvurusu
        <div class="account-name"><?php echo htmlspecialchars($kullanici_adi); ?></div>
    </div>


    <div class="application-container">
        <h3>BAŞVURULARIM</h3>

        <?php
        $sql = "SELECT pasaport_turu,surec, notlar FROM applications WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $kullanici_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0): // Eğer en az bir başvuru varsa
            while ($basvuru = $result->fetch_assoc()): ?>
                <div class="application-box">
                    <p><span class="status"><?php echo htmlspecialchars($basvuru['pasaport_turu']); ?></span></p>
                    <p><strong>Başvuru Durumu:</strong> <span class="status"><?php echo htmlspecialchars($basvuru['surec']); ?></span></p>
                    <p><strong>Yetkili Notu:</strong> <?php echo !empty($basvuru['notlar']) ? htmlspecialchars($basvuru['notlar']) : "Yetkili tarafından not eklenmemiştir."; ?></p>
                </div>
            <?php endwhile;
        else: ?>
            <p>Henüz bir başvurunuz bulunmamaktadır.</p>
            <a href="basvuruyapsayfa.php">Başvuru yapmak için tıklayın.</a>
        <?php endif; ?>
    </div>

    </div>

</body>

</html>