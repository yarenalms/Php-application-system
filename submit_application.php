<?php
session_start();
include 'db.php'; // Veritabanı bağlantısı

// Başvuru yapmak için giriş yapılmış olmalı
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Yüklenen belgeyi doğrular ve tahmin edilemez bir adla kaydeder; geçersizse NULL döndürür.
// Orijinal dosya adı kullanılmaz: aksi halde biri "shell.php" yükleyip sunucuda kod çalıştırabilir.
function belgeYukle(string $alan): ?string
{
    if (empty($_FILES[$alan]['name']) || $_FILES[$alan]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $izinliUzantilar = ['jpg', 'jpeg', 'png', 'pdf'];
    $izinliTurler    = ['image/jpeg', 'image/png', 'application/pdf'];
    $uzanti = strtolower(pathinfo($_FILES[$alan]['name'], PATHINFO_EXTENSION));
    $tur    = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$alan]['tmp_name']);
    if (!in_array($uzanti, $izinliUzantilar, true) || !in_array($tur, $izinliTurler, true)) {
        return null;
    }
    if ($_FILES[$alan]['size'] > 5 * 1024 * 1024) { // en fazla 5 MB
        return null;
    }
    $yol = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $uzanti;
    return move_uploaded_file($_FILES[$alan]['tmp_name'], $yol) ? $yol : null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    
    // Kullanıcının fakültesini çek
    $query = "SELECT faculty FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($faculty);
    $stmt->fetch();
    $stmt->close();

    $pasaport_turu = $_POST['pasaport_turu'];
    $kadro = $_POST['kadro'];
    $unvan = $_POST['unvan'];
    $kurum_sicil_no = $_POST['kurum_sicil_no'];
    $tel_no = $_POST['tel_no'];
    $email = $_POST['email'];
    $nufus_mudurlugu = $_POST['nufus_mudurlugu'];
    $esi_var_mi = $_POST['esi_var_mi'];
    $cocuk_sayisi = max(0, min(10, intval($_POST['cocuk_sayisi'] ?? 0)));
    $surec = "Bekliyor"; 

    // Kimlik fotokopisi yükleme işlemi
    $kimlik_fotokopisi = belgeYukle("kimlik_fotokopisi");
    if ($kimlik_fotokopisi === null) {
        echo "<script>alert('Kimlik fotokopisi JPG, PNG veya PDF olmalı (en fazla 5 MB).'); window.location.href='basvuruyapsayfa.php';</script>";
        exit();
    }

    // Eşin kimlik fotokopisi yükleme
    $es_kimlik_fotokopisi = ($esi_var_mi == "Var") ? belgeYukle("es_kimlik_fotokopisi") : null;

    // 📌 **Başvuruyu veritabanına ekle (faculty sütunu eklendi)**
    $sql = "INSERT INTO applications (user_id, faculty, pasaport_turu, kadro, unvan, kurum_sicil_no, tel_no, email, nufus_mudurlugu, kimlik_fotokopisi, esi_var_mi, es_kimlik_fotokopisi, cocuk_sayisi, surec) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssssssssss", $user_id, $faculty, $pasaport_turu, $kadro, $unvan, $kurum_sicil_no, $tel_no, $email, $nufus_mudurlugu, $kimlik_fotokopisi, $esi_var_mi, $es_kimlik_fotokopisi, $cocuk_sayisi, $surec);
    $stmt->execute();

    $application_id = $stmt->insert_id;

    // 📌 **Çocukların kimlik fotokopilerini kaydet**
    for ($i = 1; $i <= $cocuk_sayisi; $i++) {
        $cocuk_kimlik_fotokopisi = belgeYukle("cocuk_kimlik_fotokopisi_$i");
        if ($cocuk_kimlik_fotokopisi !== null) {

            $sql = "INSERT INTO cocuklar (application_id, kimlik_fotokopisi) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $application_id, $cocuk_kimlik_fotokopisi);
            $stmt->execute();
        }
    }

    header("Location: kullanicisayfa.php?success=1");
    exit();
}
?>



