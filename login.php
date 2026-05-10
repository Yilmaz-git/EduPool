<?php
session_start(); // Mevcut oturumu (session) baslatir 

$conn = new mysqli("localhost","root","","edu_pool"); //mysqli sinifini kullanarak edu_pool veritabanina baglan

if(isset($_POST['login'])){ //giris yap butonuna tiklandiysa
    $email = $_POST['email']; //formdan gelen emaili degiskene ata
    $password = $_POST['password']; //girilen sifreyi degiskene ata

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND password='$password'"); //userss tablosundan (veri tabanindan) email ve sifreye ait bilgileri ceker
    
    if($result->num_rows > 0){ //kullanici mevcutsa
        $user = $result->fetch_assoc();  //bilgileri dizi olarak alir
        $_SESSION['user_id'] = $user['id']; //id'yi otutuma kaydeder
        header("Location: list.php"); //kullaniciyi list sayfasina gonderir
        exit(); //kodu durdur
    } else { //kullanici mevcut degilse
        $error = "Hatalı giriş!"; //hata mesaji
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>EduPool • Giriş</title>

<style>
    /*sayfanini genel gorunumu*/
body{
    margin:0;
    font-family:'Segoe UI';
    background: linear-gradient(135deg,#1f1c2c,#928dab);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

   /*login formunun gorunumu*/
.login-box{
    background:white;
    padding:40px;
    border-radius:20px;
    width:350px;
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
    text-align:center;
}
  
   /* Logo metninin boyutu ve kalinligi */
.logo{
    font-size:28px;
    font-weight:bold;
    margin-bottom:20px;
}

    /* giris kutusunun gorunumu */
input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:1px solid #ddd;
    font-size:14px;
}

   /* butonuun gorunumu  */
button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:10px;
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;
    font-size:16px;
    cursor:pointer;
}

button:hover{
    opacity:0.9; /* Fare uzerine geldiginde butonun hafif saydamlasmasi efekti */
}

.error{
    color:red;  /* Hata mesajini kirmizi goster */
    margin-top:10px;
}

.footer{ /* Alt kisimdaki "Kayit ol" yazisinin stili */
    margin-top:15px;
    font-size:13px;
}

.footer a{ /* Alttaki footerda a linkinin gorunumu */
    color:#ee0979;
    text-decoration:none;
}
</style>

</head>

<body>

<div class="login-box"> <!--login kutusunu ayir (ayri bir kutu gibi)--> 

<div class="logo">📚 EduPool</div> <!--logoyu ayir-->

<form method="POST"> <!-- Verileripost metodu ile gizli sekilde bu sayfanin kendisine gonder -->

<input type="email" name="email" placeholder="Email" required> <!-- Kullanicinin emailini yazacagi alan (required ile bos birakilmasi engellenir) -->

<input type="password" name="password" placeholder="Şifre" required> <!-- sifrenini girilecegi alan (type password olunca otomatik gizli oldu)-->

<button name="login">Giriş Yap</button> <!-- formu gonderen 'giris yap' butonu -->

</form>

<?php if(isset($error)){ ?>  <!-- hatali giris yapildiysa (error degiskeni doluysa) -->
<div class="error"><?php echo $error; ?></div> <!-- error mesajini yazdir -->
<?php } ?>

<!-- kayitli kullanici degilse kayit olma sayfasina yonlendirecek linki footera yaz -->
<div class="footer">
Hesabın yok mu? <a href="register.php">Kayıt ol</a>
</div>

</div>

</body>
</html>