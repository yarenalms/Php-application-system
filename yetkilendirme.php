<?php
session_start();
include 'db.php';

// Bu sayfa kullanıcıların kişisel bilgilerini listeler: sadece yetkililer görebilir
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

$yetkili_adi = $_SESSION['user_name'];

// Sadece normal kullanıcıları getir (role != 1)
$query = "SELECT id, ad_soyad, email, tc, telefon, faculty, position FROM users WHERE role != 1";
$result = $conn->query($query);

// Fakülteleri listele
$departments = [
    "Mühendislik Fakültesi", "İktisadi ve İdari Bilimler Fakültesi", "Fen Edebiyat Fakültesi",
    "Sağlık Bilimleri Fakültesi", "İlahiyat Fakültesi", "Ziraat Fakültesi",
    "Güzel Sanatlar Fakültesi", "Hukuk Fakültesi", "İletişim Fakültesi"
];

// Sabit pozisyon seçenekleri
$positions = ["İşçi", "Memur", "Akademik Personel"];
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yetkilendirme</title>
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

        /* Tablo Alanı */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #1d3b63;
            color: white;
            padding: 12px;
        }

        td {
            padding: 10px;
        }

        select, .button {
            padding: 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Kaydet Butonu */
        .save-button {
            display: block;
            width: 15%;
            padding: 12px;
            background-color: #d9534f;
            color: white;
            text-align: center;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .save-button:hover {
            background-color: #c9302c;
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
        <a href="sonuclananbasvuru.php">SONUÇLANAN BAŞVURULAR</a>
        <a href="#" class="active">KULLANICI YETKİLENDİRME</a>
        <a href="index.php" class="logout-button">ÇIKIŞ</a>
    </div>

    <div class="header">
       # <br> Hususi/Hizmet Pasaport Başvurusu
        <div class="account-name"><?php echo htmlspecialchars($yetkili_adi); ?></div>
    </div>

    <div class="content">
        <div class="table-container">
            <h3>Kayıtlı Kullanıcılar</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Ad Soyad</th>
                    <th>Email</th>
                    <th>TC</th>
                    <th>Telefon</th>
                    <th>Bölüm</th>
                    <th>Pozisyon</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['ad_soyad']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['tc']); ?></td>
                        <td><?php echo htmlspecialchars($row['telefon']); ?></td>
                        <td>
                            <select onchange="saveChange(<?php echo $row['id']; ?>, 'faculty', this.value)">
                                <option value="">Seçiniz</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department; ?>" <?php echo ($row['faculty'] == $department) ? 'selected' : ''; ?>><?php echo $department; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <select onchange="saveChange(<?php echo $row['id']; ?>, 'position', this.value)">
                                <option value="">Seçiniz</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?php echo $position; ?>" <?php echo ($row['position'] == $position) ? 'selected' : ''; ?>><?php echo $position; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <button class="save-button" onclick="saveAll()">KAYDET</button>
        </div>
    </div>

    <script>
        let userChanges = {};

        function saveChange(userId, field, value) {
            if (!userChanges[userId]) {
                userChanges[userId] = {};
            }
            userChanges[userId][field] = value;
        }

        function saveAll() {
            if (Object.keys(userChanges).length === 0) {
                alert("Değişiklik yapılmadı!");
                return;
            }

            fetch('update_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(userChanges)
            }).then(response => response.json())
              .then(data => {
                  alert("Değişiklikler kaydedildi!");
                  location.reload();
              })
              .catch(error => console.error("Hata:", error));
        }
    </script>
</body>
</html>
