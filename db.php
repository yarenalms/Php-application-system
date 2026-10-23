<?php
$host = "localhost";
$user = "root"; // XAMPP varsayılan kullanıcı
$password = ""; // Şifre yok
$database = "pasaport_sistemi";

// Veritabanına bağlan
$conn = new mysqli($host, $user, $password, $database);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>
