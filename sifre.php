<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $tc = $_POST['tc'] ?? '';
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Şifreler eşleşiyor mu kontrol et
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Şifreler uyuşmuyor!'); window.location.href='sifre.php';</script>";
        exit();
    }

    // Yeni şifreyi hashle
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Hesabın sahibi olduğunu doğrulamak için e-posta ile birlikte TC kimlik numarası da eşleşmeli.
    // (Gerçek bir sistemde bunun yerine e-postaya tek kullanımlık sıfırlama bağlantısı gönderilmelidir.)
    $query = "SELECT id FROM users WHERE email = ? AND tc = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $tc);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Kullanıcı bulundu, şifreyi güncelle
        $stmt->close();
        $update_query = "UPDATE users SET sifre = ? WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $hashed_password, $email);
        
        if ($update_stmt->execute()) {
            echo "<script>alert('Şifre başarıyla güncellendi!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Hata oluştu! Tekrar deneyin.'); window.location.href='sifre.php';</script>";
        }

        $update_stmt->close();
    } else {
        echo "<script>alert('E-posta veya TC kimlik numarası hatalı!'); window.location.href='sifre.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Değiştir</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }
        input {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button {
            width: 50%;
            padding: 10px;
            background-color: #1d3b63;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #162c47;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Şifre Değiştir</h2>
        <form action="sifre.php" method="POST">
            <input type="email" name="email" placeholder="E-Posta" required>
            <input type="text" name="tc" placeholder="TC Kimlik No" pattern="\d{11}" maxlength="11" required>
            <input type="password" name="new_password" placeholder="Yeni Şifre" required>
            <input type="password" name="confirm_password" placeholder="Yeni Şifre (Tekrar)" required>
            <button type="submit" class="button">Şifreyi Güncelle</button>
        </form>
    </div>
</body>
</html>
