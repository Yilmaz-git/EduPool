<?php

// Session baslatilir
session_start();


// ======================
// ADMIN KONTROLU
// ======================

// Sadece admin kullanicilar bu sayfaya girebilir
if($_SESSION['role'] != 'admin'){

    // Yetkisiz erisim mesaji verir
    die("Yetkisiz erisim");
}



// ======================
// VERITABANI BAGLANTISI
// ======================

// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");



// ======================
// KULLANICI SILME
// ======================

// URL'de delete parametresi varsa
if(isset($_GET['delete'])){


    // Silinecek kullanici id'si alinir
    $id = $_GET['delete'];


    // Admin kendi hesabini silemez
    if($id != $_SESSION['user_id']){


        // users tablosundan kullanici silinir
        $conn->query("DELETE FROM users WHERE id=$id");
    }


    // Sayfa yeniden yuklenir
    header("Location: manage_users.php");
}



// ======================
// KULLANICILARI CEKME
// ======================

// users tablosundaki tum kullanicilar cekilir
$result = $conn->query("SELECT * FROM users");

?>

<!DOCTYPE html>
<html>

<head>

<!-- Sayfa basligi -->
<title>Kullanicilar</title>

<style>

/* Sayfa genel tasarimi */
body{
    margin:0;
    font-family:'Segoe UI';
    background:linear-gradient(135deg,#1f1c2c,#928dab);
}


/* Ana container */
.container{
    width:80%;
    margin:auto;
    margin-top:40px;
}


/* Kullanici karti */
.card{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-top:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}


/* Silme butonu */
.btn{
    background:red;
    color:white;
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
}


/* Rol yazisi */
.role{
    font-weight:bold;
}

</style>

</head>

<body>


<!-- Ana container -->
<div class="container">


<!-- Sayfa basligi -->
<h1 style="color:white;">👥 Kullanici Yonetimi</h1>



<!-- ======================
     KULLANICILARI LISTELEME
====================== -->

<?php while($row = $result->fetch_assoc()){ ?>


<!-- Kullanici karti -->
<div class="card">

<div>


<!-- Kullanici adi -->
<b><?php echo $row['username']; ?></b>

<br>


<!-- Email bilgisi -->
<?php echo $row['email']; ?>

<br><br>


<!-- Kullanici rolu -->
<span class="role">


<?php if($row['role']=="admin"){ ?>

👑 Admin

<?php } else { ?>

👤 User

<?php } ?>

</span>

</div>

<div>


<!-- Admin kendi hesabini silemez -->
<?php if($row['id'] != $_SESSION['user_id']){ ?>


<!-- Kullanici silme butonu -->
<a class="btn"
href="manage_users.php?delete=<?php echo $row['id']; ?>">

Sil

</a>

<?php } ?>

</div>

</div>

<?php } ?>

</div>

</body>
</html>