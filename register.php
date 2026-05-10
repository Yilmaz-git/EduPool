<?php
$conn = new mysqli("localhost", "root", "", "edu_pool"); //mysqli sinifini kullanarak edu_pool veritabanina baglan
if ($conn->connect_error) die("Bağlantı hatası"); // baglanti sirasinda sorun olusursa durdur ve hata mesaji bas

if ($_SERVER["REQUEST_METHOD"] == "POST") { //Eger kullanici kayit ol butonuna bastiysa
    $username = $_POST['username']; // Formdaki kullanici adi kutusuna yazilan degeri alir (post metodu ile (gizli/guvenli))
    $email = $_POST['email']; //Formdaki mail kutusuna yazilan degeri alir
    $password = $_POST['password']; //Formdaki sifre kutusuna yazilan degeri alir

    // Veritabanındaki 'users' tablosuna yeni satir eklemek icin sorgu hazirla ve calistir
    $conn->query("INSERT INTO users (username, email, password) 
                  VALUES ('$username', '$email', '$password')"); // values: namelere yazilacak degerler

    header("Location: login.php"); //kayittan sonra kullaniciyi login sayfasina yonlendir
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kayıt Ol</title> <!-- sayfa bilgisi -->
    <style>
        body { font-family: Arial; background:#f5f5f5; padding:20px; } /* Sayfanin genel yazi tipi, arka plan rengi ve ic boslugu */
        form { background:white; padding:20px; border-radius:10px; width:300px; } /*Formun beyaz kutu gorunumu, koselerinin yuvarlanmasi ve genisligi */
        input { width:100%; margin:5px 0; padding:8px; } /*input kutusunun gorunumu */
        button { background:green; color:white; padding:10px; border:none; } /*buton gorunumu(kutu yesil , yazi beyaz)*/
    </style>
</head>
<body>

<h2>Kayıt Ol</h2> <!-- sayfa basligi -->

<form method="POST"> <!--post metodu ile (gizli) verilerin ayni sayfaya gonderimini saglar-->
    <input type="text" name="username" placeholder="Kullanıcı Adı" required> <!-- Kullanici adi alani: required ozelligi bu alanin bos birakilmasini engeller -->
    <input type="email" name="email" placeholder="Email" required> <!-- Email alani dogru formatta (orn: @ isareti) giris yapilmasini zorunlu yapar -->
    <input type="password" name="password" placeholder="Şifre" required> <!-- sifre alani: Yazilan karakterlerin yildiz veya nokta seklinde gizlenmesini saglar -->
    <button type="submit">Kayıt Ol</button> <!-- kayit olma butonu -->
</form>

</body>
</html>