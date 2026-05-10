<?php
session_start(); // Mevcut oturumu (session) baslatir veya var olan oturumu yakalar
session_destroy(); // O anki kullaniciya ait tum oturum verilerini (ID, kullanici adi, yetki..) siler
header("Location: login.php"); //kullaniciyi login sayfasina yonlendirir
?>