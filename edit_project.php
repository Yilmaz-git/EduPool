<?php

// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");



// ======================
// URL'DEN ID ALMA
// ======================

// URL'den gelen id degeri alinir
// Eger id yoksa varsayilan olarak 0 kullanilir
$id = $_GET['id'] ?? 0;



// ======================
// PROJE VERISINI CEKME
// ======================

// projects tablosundan belirtilen id'ye ait kayit cekilir
$result = $conn->query("SELECT * FROM projects WHERE id=$id");


// Gelen veri associative array olarak alinir
$p = $result->fetch_assoc();




// ======================
// GUNCELLEME ISLEMI
// ======================

// Eger formdaki update butonuna basildiysa
if(isset($_POST['update'])){


    // Formdan gelen title bilgisi alinir
    $title = $_POST['title'];


    // Formdan gelen description bilgisi alinir
    $desc = $_POST['description'];


    // Formdan gelen how_made bilgisi alinir
    $how = $_POST['how'];



    // UPDATE sorgusu hazirlanir
    $stmt = $conn->prepare("
    UPDATE projects SET title=?, description=?, how_made=? WHERE id=?
    ");


    // Parametreler baglanir
    // s = string
    // i = integer
    $stmt->bind_param("sssi",$title,$desc,$how,$id);


    // SQL sorgusu calistirilir
    $stmt->execute();



    // Guncelleme sonrasi projects.php sayfasina yonlendirme yapilir
    header("Location: projects.php");


    // Script tamamen durdurulur
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Duzenle</title>

<style>

/* Sayfanin genel tasarimi */
body{
    font-family:'Segoe UI';
    background:#f5f5f5;
    padding:40px;
}


/* Form kutusu */
.box{
    background:white;
    padding:20px;
    border-radius:15px;
    width:400px;
    margin:auto;
}


/* Input ve textarea tasarimi */
input,textarea{
    width:100%;
    margin:10px 0;
    padding:10px;
    border-radius:8px;
    border:1px solid #ddd;
}


/* Buton tasarimi */
button{
    background:#ee0979;
    color:white;
    padding:10px;
    border:none;
    border-radius:10px;
    width:100%;
}

</style>
</head>

<body>

<!-- Ana kutu -->
<div class="box">

<!-- Sayfa basligi -->
<h2>Projeyi Duzenle</h2>


<!--
method="POST"
Veriler gizli sekilde gonderilir
-->
<form method="POST">


<!--
title degeri input icinde gosterilir
Kullanici mevcut veriyi gorup degistirebilir
-->
<input name="title" value="<?php echo $p['title']; ?>">


<!-- description verisi textarea icinde gosterilir -->
<textarea name="description"><?php echo $p['description']; ?></textarea>


<!-- how_made verisi textarea icinde gosterilir -->
<textarea name="how"><?php echo $p['how_made']; ?></textarea>


<!-- Formu gonderen buton -->
<button name="update">Guncelle</button>

</form>
</div>

</body>
</html>