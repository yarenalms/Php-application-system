<?php
session_start();
include 'db.php';

// Yetkilinin giriş yapıp yapmadığını kontrol et
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: index.php");
    exit();
}

// Başvuru ID'sini al
if (!isset($_POST['basvuru_id'])) {
    echo "Başvuru ID'si bulunamadı.";
    exit();
}

$basvuru_id = $_POST['basvuru_id'];
$not = isset($_POST['not']) ? $_POST['not'] : '';
$durum = isset($_POST['onayla']) ? 'Onaylandı' : 'Reddedildi';

// Başvuruyu güncelle
$update_sql = "UPDATE applications SET surec = ?, notlar = ? WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssi", $durum, $not, $basvuru_id);

if ($update_stmt->execute()) {
    echo "<script>alert('Başvuru başarıyla $durum olarak güncellendi!'); window.location.href='yetkilisayfa.php';</script>";
    exit();
} else {
    echo "Hata: " . $conn->error;
}
?>
