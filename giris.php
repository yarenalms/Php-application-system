<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kullanıcıyı veritabanında ara
    $query = "SELECT id, ad_soyad, sifre, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $ad_soyad, $hashed_password, $role);
        $stmt->fetch();

        // Şifreyi doğrula
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $ad_soyad;
            $_SESSION['role'] = $role;

            // Kullanıcının rolüne göre yönlendir
            if ($role == 1) {
                header("Location: yetkilisayfa.php");
            } else {
                header("Location: kullanicisayfa.php");
            }
            exit();
        } else {
            echo "<script>alert('Hatalı şifre!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('E-posta adresi bulunamadı!'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
