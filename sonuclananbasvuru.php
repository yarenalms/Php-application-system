<?php
session_start();
include 'db.php';

// Yetkilinin giriş yapıp yapmadığını kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

$yetkili_id = $_SESSION['user_id'];
$yetkili_adi = $_SESSION['user_name'];

// Yetkilinin fakültesini al
$query = "SELECT faculty FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $yetkili_id);
$stmt->execute();
$stmt->bind_result($yetkili_birim);
$stmt->fetch();
$stmt->close();

// Onaylanan ve reddedilen başvuruları çek (Yetkilinin fakültesine göre filtrelenmiş)
$sql = "SELECT applications.id, applications.surec, applications.notlar, users.ad_soyad, applications.unvan 
        FROM applications 
        JOIN users ON applications.user_id = users.id 
        WHERE applications.surec IN ('Onaylandı', 'Reddedildi') 
        AND users.faculty = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $yetkili_birim);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sonuçlanan Başvurular</title>
    <style>
        /* Genel Sayfa Stili */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }

        /* Üst Menü (Header) */
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
            top: 90px;
            background-color: white;
            color: black;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
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
            width: 600px;
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

        /* Yetkili Notu */
        .note-box {
            font-size: 14px;
            font-style: italic;
            color: #333;
            margin-top: 5px;
        }

        /* Durum Butonları */
        .status-button {
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: default;
            text-align: center;
        }

        /* Onaylandı Butonu */
        .approved {
            background-color: #5cb85c;
            color: white;
        }

        /* Reddedildi Butonu */
        .rejected {
            background-color: #d9534f;
            color: white;
        }

        /* Detay Butonu */
        .details-button {
            background-color: #1d3b63;
            color: white;
            border: none;
            padding: 8px 12px;
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
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="#" alt="Üniversite Logosu">
        <div class="divider"></div>
        <a href="yetkilisayfa.php">BEKLEYEN BAŞVURULAR</a>
        <a href="#" class="active">SONUÇLANAN BAŞVURULAR</a>
        <a href="yetkilendirme.php">KULLANICI YETKİLENDİRME</a>
        <a href="index.php" class="logout-button">ÇIKIŞ</a>
    </div>

    <div class="header">
        # <br> Sonuçlanan Başvurular
        <div class="account-name"><?php echo htmlspecialchars($yetkili_adi); ?></div>
    </div>

    <div class="content">
        <h3><?php echo htmlspecialchars($yetkili_birim); ?> Fakültesine Ait Sonuçlanan Başvurular</h3>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="application-box">
                <div>
                    <strong><?php echo htmlspecialchars($row['ad_soyad']); ?></strong> - <?php echo htmlspecialchars($row['unvan']); ?>
                    <p class="note-box"><strong>Yetkili Notu:</strong> <?php echo !empty($row['notlar']) ? htmlspecialchars($row['notlar']) : "Yetkili tarafından not eklenmemiştir."; ?></p>
                </div>
                <button class="status-button <?php echo ($row['surec'] == 'Onaylandı') ? 'approved' : 'rejected'; ?>">
                    <?php echo htmlspecialchars($row['surec']); ?>
                </button>
                <button class="details-button" onclick="goToDetails(<?php echo $row['id']; ?>)">⬇</button>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function goToDetails(id) {
            window.location.href = "kisibilgileri.php?id=" + id;
        }
    </script>
</body>
</html>
