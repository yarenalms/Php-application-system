<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $tc       = trim($_POST['tc'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $faculty  = trim($_POST['faculty'] ?? '');
    $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT);
    $role     = 0; // Kayıt olan herkes normal kullanıcıdır; yetkili rolü sadece veritabanından verilir

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\d{11}$/', $tc)) {
        echo "<script>alert('Lütfen bilgileri eksiksiz ve doğru girin.'); window.location.href='kayit.php';</script>";
        exit();
    }

    // 1) E-posta daha önce kullanılmış mı? (prepared statement: kullanıcı verisi SQL'e karışmaz)
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('Bu e-posta adresi zaten kayıtlı! Farklı bir e-posta kullanın.'); window.location.href='kayit.php';</script>";
        exit();
    }
    $check->close();

    // 2) Kullanıcıyı ekle
    $stmt = $conn->prepare(
        "INSERT INTO users (ad_soyad, email, sifre, tc, telefon, role, faculty) VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("sssssis", $name, $email, $password, $tc, $phone, $role, $faculty);

    if ($stmt->execute()) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $conn->insert_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['role']      = $role;

        header("Location: kullanicisayfa.php");
        exit();
    }

    // Veritabanı hata ayrıntıları kullanıcıya gösterilmez (saldırgana bilgi vermemek için)
    error_log("Kayıt hatası: " . $stmt->error);
    echo "<script>alert('Kayıt sırasında bir hata oluştu. Bu TC ile kayıtlı bir hesap olabilir.'); window.location.href='kayit.php';</script>";
}
?>
