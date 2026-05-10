<?php
session_start(); // Oturum yonetimini baslatir.
if (!isset($_SESSION['user_id'])) { // Kullanici giris yapmamissa (session yoksa) 
    header("Location: login.php"); //giris sayfasina yonlendir
    exit(); // kodu durdur
}

// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");
if ($conn->connect_error) die("Bağlantı hatası"); //basarisizsa program durur

// Giris yapan kullanicinin bilgilerini veritabanindan ceker.
$user_id = $_SESSION['user_id'];  // Oturumdan giris yapan kullanicinin ID'sini al
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

/* PROJE EKLEME ISLEMI */
if(isset($_POST['add'])){ // Eger formdaki ekle butonuna basilmissa
    $title = $_POST['title'] ?? ''; 
    $desc  = $_POST['description'] ?? '';
    $how   = $_POST['how'] ?? ''; //girilen verileri al bos birakilmissa ' ' ata

    // DOSYA YUKLEME KONTROLU
    $file_path=""; // Varsayilan dosya yolunu bos birak
    if(isset($_FILES['file']) && $_FILES['file']['error']==0){ // eger dosya secilmisse ve hata yoksa
        $folder="uploads/"; // Dosyalarin kaydedilecegi klasor
        if(!is_dir($folder)) mkdir($folder,0777,true); // Eger bu isimde bir klasor yoksa, mkdir ile olustur (0777: tam yetki, true: ic ice klasorlere izin ver)
       // Dosya isminin basina yuklenme zamanini (time) ekleyerek benzersiz bir dosya adi olustur (Ayni isimli dosyalar cakismasin diye)
        $name=time()."_".basename($_FILES['file']['name']); 
        move_uploaded_file($_FILES['file']['tmp_name'],$folder.$name); // Gecici (tmp) dizindeki dosyayi hedef klasore tasi
        $file_path=$folder.$name; // Veritabanina kaydedilecek dosya yolunu guncelle
    }
    // Guvenli veri ekleme (Prepared Statement)
    $stmt = $conn->prepare("INSERT INTO projects (user_id,title,description,how_made,file_path) VALUES (?,?,?,?,?)"); //eklenecek yerleri soru isareti ile doldurur
    $stmt->bind_param("issss",$user_id,$title,$desc,$how,$file_path); //atamayi parametre turune gore yapar (issss: bir int dort string)
    $stmt->execute(); 
}

/* LIKE (toggle) SISTEMI */
if(isset($_GET['like'])){
    $pid=(int)$_GET['like'];
    $chk=$conn->query("SELECT id FROM likes WHERE user_id=$user_id AND project_id=$pid");
    // Kullanici bu projeyi zaten begenmis mi kontrol et
    if($chk->num_rows>0){ // Begenmisse begeniyi kaldir 
        $conn->query("DELETE FROM likes WHERE user_id=$user_id AND project_id=$pid");
    }else{ //begenmemisse yeni begeni ekle
        $conn->query("INSERT INTO likes (user_id,project_id) VALUES ($user_id,$pid)");
    }
}

/* BOOKMARK (toggle) (KAYDETME SISTEMI) */
if(isset($_GET['save'])){
    $pid=(int)$_GET['save']; //URL den gelen save siimli parametrenin degerini alir (int guvenlik onlemi)
    $chk=$conn->query("SELECT id FROM bookmarks WHERE user_id=$user_id AND project_id=$pid"); //bookmarks (kaydedilenler) tablosuna git ve oradaki ID degerlerini getir
    if($chk->num_rows>0){ // Zaten kayitliysa sil
        $conn->query("DELETE FROM bookmarks WHERE user_id=$user_id AND project_id=$pid");
    }else{ //degilse kaydet
        $conn->query("INSERT INTO bookmarks (user_id,project_id) VALUES ($user_id,$pid)");
    }
}

/* YORUM EKLEME ISLEMI */
if(isset($_POST['comment'])){  //yorum gonderilmisse
    $pid=(int)$_POST['pid'];  // Hangi projeye yorum yapildiginin gizli (hidden) inputtan gelen id'si
    $text=$_POST['comment'] ?? ''; // Yorum metni (bossa ' ' ata)
    if($text!==''){ // Eger yorum bos gonderilmemisse
        $stmt=$conn->prepare("INSERT INTO comments (user_id,project_id,comment) VALUES (?,?,?)"); //once parametreleri soru isareti ile doldur(guvenlik icin)
        $stmt->bind_param("iis",$user_id,$pid,$text); //iis ye gore(int int string) parametreleri bagla
        $stmt->execute();
    }
}

// TUm projeleri ve bu projelere ait toplam beGeni/kaydedilme sayilarini ceken gelismis sorgu
$projects = $conn->query(" 
SELECT p.*,
 (SELECT COUNT(*) FROM likes l WHERE l.project_id=p.id) as like_count,
 (SELECT COUNT(*) FROM bookmarks b WHERE b.project_id=p.id) as save_count
FROM projects p
ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>EduPool • Projeler & Ödevler</title>

<style>
    /* GENEL TASARIM AYARLARI */
body{
    margin:0;
    font-family:'Segoe UI';
    background: linear-gradient(135deg,#1f1c2c,#928dab); /* Arka plan gecisli renk */
}

/* NAVBAR (ust menu) */ 
.navbar{
    background: rgba(255,255,255,0.1); /*yari saydam beyaz arka plan*/
    backdrop-filter: blur(15px); /*arkada kalanlari bulanik yap*/
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between; /* icerikleri saga ve sola yasla */
}
/*ust menudeki linklerin gorunumu*/
.navbar a{color:white;text-decoration:none;margin-left:10px;}
.navbar a:hover{text-decoration:underline;}

/* CONTAINER (ana icerik kutusu)*/
.container{width:85%;margin:auto;margin-top:30px;}

/* ADD BUTTON (ekle butonu) */
.add-btn{
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;border:none;padding:10px 18px;border-radius:12px;cursor:pointer;
}

/* GRID (kartlarin yanyana dizilmesi icin izgara sistemi)*/
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:20px;
}

/* PROJE KARTLARI TASARIMI */
.card{
    background:white;
    padding:18px;
    border-radius:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2); /* Karta hafif golge efekti */
    transition:0.3s; /* Hover efektinin yumusak olmasi icin sure */
}
.card:hover{transform:translateY(-6px) scale(1.02);} /* uzerine gelince karti hafifce buyut ve yukari kaldir */
.card h3{margin:0 0 8px 0;} /*card icindeki bsaliklarin gorunumu*/
.meta{font-size:12px;color:#777;margin-bottom:8px;} /*projenin ID'si ve tarihi gibi ikincil bilgileri (metadata) iceren kismin stili*/
.how{background:#f7f7f7;padding:10px;border-radius:10px;margin:8px 0;} /*"Nasil yaptim" bolumunu kutu icine alarak diger metinlerden ayir*/

/* ACTIONS (butonlar eylem alani) */
.actions{
    display:flex;justify-content:space-between;align-items:center;margin-top:10px;
}
.left a{
    margin-right:10px;
    padding:6px 10px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    font-size:13px;
}
.like{background:#e74c3c;}
.save{background:#3498db;}
.count{font-size:12px;color:#555;}

/* COMMENTS (yorumlar alani tasarimi) */
.comments{margin-top:10px;}
.comments p{margin:4px 0;font-size:13px;}
.comments input{
    width:100%;padding:6px;border-radius:8px;border:1px solid #ddd;
}

/* MODAL (acilir pencere tasarimi) */
#modal{
    display:none; /* Baslangicta gizli tut */
    position:fixed;top:0;left:0;width:100%;height:100%;
    background:rgba(0,0,0,0.6); /* Arka plani koyulastir */
    justify-content:center;align-items:center; /* Modali ortaya hizala */
}
.modal-box{
    background:white;padding:25px;border-radius:20px;width:380px;
}
.modal-form{display:flex;flex-direction:column;gap:10px;}
.modal-form input,.modal-form textarea{
    padding:10px;border-radius:10px;border:1px solid #ddd;
}
.save-btn{
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;border:none;padding:10px;border-radius:10px;cursor:pointer;
}
.close-btn{float:right;cursor:pointer;}

/* MOBIL CIHAZ UYUMU (RESPONSIVE) */
@media(max-width:768px){
    .container{width:95%;} /* mobilde ekranin daha buyuk kismini kapla */
}
</style>
</head>

<body>

<!-- UST MENU HTML KODLARI -->
<div class="navbar">
    <div>
        📚 EduPool |
        <a href="list.php">Ana Sayfa</a> |
        <a href="projects.php">Projeler & Ödevler</a>
    </div>
    <div>
        <!-- PHP ile oturumdaki kullanicinin ismini yazdir -->
        👤 <?php echo $user['username']; ?> |
        <a href="logout.php">Çıkış</a> 
    </div>
</div>

<div class="container">

<!-- BASLIK VE EKLE BUTONU -->
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
    <h2 style="color:white;margin:0;">📚 Projeler & Ödevler</h2>
    <!-- Tiklaninca JavaScript ile openModal() fonksiyonunu calistirip modal penceresini acar -->
    <button class="add-btn" onclick="openModal()">➕ Yeni</button>
</div>

<div class="grid">

<?php while($p=$projects->fetch_assoc()){ ?> <!--tum projeleri ekrana yazdirmak icin dongu-->

<div class="card">

<h3><?php echo $p['title']; ?></h3> <!--projenin  yazdir-->
<div class="meta">ID: <?php echo $p['id']; ?> • <?php echo $p['created_at']; ?></div> <!--proje id'si ve eklenme tarihi-->

<p><?php echo $p['description']; ?></p> <!--proje aciklamasi-->

<div class="how">
<b>Nasıl yapıldı:</b><br>
<?php echo $p['how_made']; ?> <!--nasil yapildi aciklamasi-->
</div>

<?php if($p['file_path']){ ?> <!--dosya yuklenmissw acma butonu-->
<a href="<?php echo $p['file_path']; ?>" target="_blank">📎 Dosya Aç</a>
<?php } ?>

<!-- BEGENI VE SAYAC ALANI -->
<div class="actions">
    <div class="left">
        <!-- URL parametresi olarak 'like=proje_id' gondererek begenme islemini tetikler -->
        <a class="like" href="?like=<?php echo $p['id']; ?>">👍</a>
            </div>
    <div class="count">
        <!-- Veritabanindan gelen toplam begeni degerini yazdirir -->
        👍 <?php echo $p['like_count']; ?>
    </div>
</div>

<!-- SIL VE DUZENLE BUTONLARI -->
<div style="margin-top:10px;">

<a href="delete.php?id=<?php echo $p['id']; ?>&type=project"
style="background:red;color:white;padding:6px 10px;border-radius:8px;text-decoration:none;">
Sil
</a>

<a href="edit_project.php?id=<?php echo $p['id']; ?>"
style="background:orange;color:white;padding:6px 10px;border-radius:8px;text-decoration:none;margin-left:10px;">
Düzenle
</a>

</div>

<!-- YORUMLAR ALANI -->
<div class="comments">
<form method="POST"> <!-- Yorum ekleme formu -->
<input type="hidden" name="pid" value="<?php echo $p['id']; ?>"><!-- Hangi projeye yorum yapildigini bilmek icin projenin ID'sini (gizli) gonder -->
<input name="comment" placeholder="Yorum yaz...">
</form>

<?php
//BU PROJEYE AIT YORUMLARI LISTELEME
$cs=$conn->query("SELECT comment FROM comments WHERE project_id=".$p['id']);
while($c=$cs->fetch_assoc()){
echo "<p>💬 ".$c['comment']."</p>";
}
?>
</div>

</div>

<?php } ?>

</div>
</div>

<!-- YENI PROJE EKLEME EKRANI (MODAL) -->
 <!-- CSS'de baalangicta gizlenmis (display:none) durumdadir. -->
<div id="modal">
<div class="modal-box">
 <!-- Kapatma butonuna basildiginda JS ile closeModal() fonksiyonunu calistirir -->
<span onclick="closeModal()" class="close-btn">✖</span>

<h3>Yeni Proje</h3>

<!-- Dosya gonderimi icerdigi icin 'enctype="multipart/form-data"' kullanilmasi zorunludur -->
<form method="POST" enctype="multipart/form-data" class="modal-form">
<input name="title" placeholder="Başlık"> <!--baslik input girisi-->
<textarea name="description" placeholder="Açıklama"></textarea> <!--aciklama textareaa kutusu-->
<textarea name="how" placeholder="Nasıl yaptım?"></textarea> <!--nasil yaptim textarea kutusu-->
<input type="file" name="file"> <!--dosya ekleme alani-->
<button name="add" class="save-btn">Kaydet</button> <!--kaydet butonu-->
</form>

</div>
</div>

<!-- HTML ICINDEKI JAVASCRIPT KODLARI -->
<script>
// Modalin (Yeni Ekle penceresi) display (gorunurluk) ozelligini flex (gorunur) yap
function openModal(){document.getElementById("modal").style.display="flex";}
// Modalin (Yeni Ekle penceresi) display (gorunurluk) ozelligini none (gizli) yapar
function closeModal(){document.getElementById("modal").style.display="none";}
</script>

</body>
</html>