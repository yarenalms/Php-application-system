<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include 'db.php';

// Yetkili giriş yapmış mı kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

$yetkili_adi = $_SESSION['user_name'];

// Başvuru ID'sini al
if (!isset($_GET['id'])) {
    echo "Başvuru ID'si bulunamadı.";
    exit();
}

$basvuru_id = $_GET['id'];

// Başvuru bilgilerini al
$sql = "SELECT * FROM applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $basvuru_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Başvuru bulunamadı.");
}

$basvuru = $result->fetch_assoc();

// Kullanıcının adını ve TC bilgisini çek
$user_sql = "SELECT ad_soyad, tc FROM users WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $basvuru['user_id']);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
$user_name = $user_data['ad_soyad'];
$user_tc = $user_data['tc']; // TC bilgisini çekiyoruz

// **Başvuruyu Güncelleme (Yetkili Onay/Red İşlemi)**
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $not = $_POST['not'];
    $durum = isset($_POST['onayla']) ? 'Onaylandı' : 'Reddedildi';

    $update_sql = "UPDATE applications SET surec = ?, notlar = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $durum, $not, $basvuru_id);

    if ($update_stmt->execute()) {
        echo "<script>alert('Başvuru başarıyla $durum olarak güncellendi!'); window.location.href='yetkilisayfa.php';</script>";
        exit();
    } else {
        echo "Hata: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişi Bilgileri</title>
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
        

        
        .content {
            margin-left: 220px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
      
        }

        .info-box {
            width: 48%;
            padding: 15px;
            margin-left: 15px;
            border: 1px solid black;
            background-color: white;
            transform: scale(1.0);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .note-box {
            width: 48%;
            padding: 50px;
            border: 1px solid black;
            background-color: white;
            transform: scale(1.0);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .note-box textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            font-size: 14px;
            border: 1px solid black;
            border-radius: 5px;
            resize: none;
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .button-container {
            margin-top: 15px;
            text-align: center;
        }

        .action-button {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .approve {
            background-color: #1d3b63;
            color: white;
        }

        .approve:hover {
            background-color: #162c47;
        }

        .reject {
            background-color: #d9534f;
            color: white;
        }

        .reject:hover {
            background-color: #c9302c;
        }

        
    </style>
</head>

<body>
    <div class="sidebar">
        <img src="#" alt="Üniversite Logosu">
        <div class="divider"></div>
        <a href="yetkilisayfa.php" class="active">BEKLEYEN BAŞVURULAR</a>
        <a href="sonuclananbasvuru.php">SONUÇLANAN BAŞVURULAR</a>
        
        <a href="index.php" class="logout-button">ÇIKIŞ</a>
    </div>

    <div class="header">
        # <br> Hususi/Hizmet Pasaport Başvurusu
        <div class="account-name"><?php echo htmlspecialchars($yetkili_adi); ?></div>
    </div>

    <div class="content">
        <!-- Kişinin Bilgileri -->
        <div class="info-box">
            <h3><?php echo htmlspecialchars($user_name); ?></h3>
            <p><strong>BAŞVURU ID:</strong> <?php echo htmlspecialchars($basvuru['user_id']); ?></p>
            <p><strong>TC:</strong> <?php echo htmlspecialchars($user_tc); ?></p> <!-- users tablosundan çekildi -->
            <p><strong>PASAPORT TÜRÜ:</strong> <?php echo htmlspecialchars($basvuru['pasaport_turu']); ?></p>
            <p><strong>KADRO DERECESİ:</strong> <?php echo htmlspecialchars($basvuru['kadro']); ?></p>
            <p><strong>ÜNVAN:</strong> <?php echo htmlspecialchars($basvuru['unvan']); ?></p>
            <p><strong>KURUM SİCİL NO:</strong> <?php echo htmlspecialchars($basvuru['kurum_sicil_no']); ?></p>
            <p><strong>TEL NO:</strong> <?php echo htmlspecialchars($basvuru['tel_no']); ?></p>
            <p><strong>E-POSTA:</strong> <?php echo htmlspecialchars($basvuru['email']); ?></p>
            <p><strong>NÜFUS MÜDÜRLÜĞÜ:</strong> <?php echo htmlspecialchars($basvuru['nufus_mudurlugu']); ?></p>
            <p><strong>BAŞVURU TARİHİ:</strong> <?php echo htmlspecialchars($basvuru['basvuru_tarihi']); ?></p>
            <p><strong>KİMLİK FOTOKOPİSİ:</strong> <a href="<?php echo htmlspecialchars($basvuru['kimlik_fotokopisi']); ?>" download>İndir</a></p>
            <p><strong>EŞİ VAR MI?:</strong> <?php echo ($basvuru['esi_var_mi'] == "Var") ? "Evet" : "Hayır"; ?></p>

<?php if ($basvuru['esi_var_mi'] == "Var" && !empty($basvuru['es_kimlik_fotokopisi'])): ?>
    <p><strong>EŞİN KİMLİK FOTOKOPİSİ:</strong> <a href="<?php echo htmlspecialchars($basvuru['es_kimlik_fotokopisi']); ?>" download>İndir</a></p>
<?php endif; ?>

<!-- Çocuk Bilgileri -->
<p><strong>ÇOCUK SAYISI:</strong> <?php echo htmlspecialchars($basvuru['cocuk_sayisi']); ?></p>

<?php
if ($basvuru['cocuk_sayisi'] > 0):
    // Çocuk kimlik fotokopilerini çekiyoruz
    $sql_cocuklar = "SELECT kimlik_fotokopisi FROM cocuklar WHERE application_id = ?";
    $stmt_cocuklar = $conn->prepare($sql_cocuklar);
    $stmt_cocuklar->bind_param("i", $basvuru_id);
    $stmt_cocuklar->execute();
    $result_cocuklar = $stmt_cocuklar->get_result();

    $counter = 1;
    while ($cocuk = $result_cocuklar->fetch_assoc()):
?>
    <p><strong>ÇOCUK <?php echo $counter; ?> KİMLİK FOTOKOPİSİ:</strong> 
        <a href="<?php echo htmlspecialchars($cocuk['kimlik_fotokopisi']); ?>" download>İndir</a>
    </p>
<?php
    $counter++;
    endwhile;
endif;
?>
        </div>

        <!-- Yetkilinin Not Ekleme Alanı -->
        <div class="note-box">
            <h3>Yetkili Notu Ekleyiniz</h3>
            <form method="POST">
                <textarea name="not" placeholder="Başvuru hakkında not ekleyiniz..."></textarea>
                <button type="submit" class="action-button approve" name="onayla">ONAYLA</button>
                <button type="submit" class="action-button reject" name="reddet">REDDET</button>
            </form>
        </div>
    </div>
</body>

</html>