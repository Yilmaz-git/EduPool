<?php

session_start();

// Sadece admin kullanicilar silme islemi yapabilir
if($_SESSION['role'] != 'admin'){
    die("Yetkisiz erisim");
}

// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");


// Eger baglanti basarisizsa programi durdurur
if ($conn->connect_error) {
    die("Baglanti hatasi");
}



// ======================
// URL'DEN VERI ALMA
// ======================

// URL'den id degeri alinir
// Ornek:
// delete.php?id=5
$id = $_GET['id'] ?? 0;


// URL'den type degeri alinir
// Eger gelmezse varsayilan olarak "content" kullanilir
$type = $_GET['type'] ?? 'content';



// ======================
// PROJECT SILME
// ======================

// Eger type degeri "project" ise
if($type == "project"){


    // projects tablosundan belirtilen id silinir
    $conn->query("DELETE FROM projects WHERE id=$id");


    // islem bittikten sonra projects.php sayfasina yonlendirir
    header("Location: projects.php");

} else {

    // ======================
    // CONTENT SILME
    // ======================


    // contents tablosundan belirtilen id silinir
    $conn->query("DELETE FROM contents WHERE id=$id");


    // islem bittikten sonra list.php sayfasina yonlendirir
    header("Location: list.php");
}



// Scriptin devam etmesini durdurur
exit();

?>