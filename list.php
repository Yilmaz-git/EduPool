<?php

// Session baslatilir
session_start();



// ======================
// GIRIS KONTROLU
// ======================

// Eger user_id yoksa kullanici giris yapmamistir
if (!isset($_SESSION['user_id'])) {

    // login.php sayfasina yonlendirilir
    header("Location: login.php");

    // Kodun devam etmesi durdurulur
    exit();
}



// ======================
// VERITABANI BAGLANTISI
// ======================

// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");


// Baglanti hatasi varsa program durur
if ($conn->connect_error) die("Baglanti hatasi");



// ======================
// KULLANICI BILGISI CEKME
// ======================

// Session'daki kullanici id'si alinir
$user_id = $_SESSION['user_id'];


// users tablosundan kullanici bilgileri cekilir
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();



// ======================
// FILTRE PARAMETRELERI
// ======================

// URL'den type parametresi alinir
$type = $_GET['type'] ?? '';


// URL'den search parametresi alinir
$search = $_GET['search'] ?? '';


// URL'den lesson parametresi alinir
$lesson = $_GET['lesson'] ?? '';



// ======================
// SQL SORGUSU
// ======================

// contents ve lessons tablolari birlestirilir
$sql = "
SELECT contents.*, lessons.lesson_name 
FROM contents 
LEFT JOIN lessons ON contents.lesson_id = lessons.id
WHERE 1=1
";


// Eger type secildiyse filtre eklenir
if($type != ""){
    $sql .= " AND contents.type = '$type'";
}


// Eger search bos degilse aciklamada arama yapilir
if($search != ""){
    $sql .= " AND contents.description LIKE '%$search%'";
}


// Eger lesson girildiyse ders adinda arama yapilir
if($lesson != ""){
    $sql .= " AND lessons.lesson_name LIKE '%$lesson%'";
}


// SQL sorgusu calistirilir
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

<title>EduPool</title>

<style>

/* Sayfa genel tasarimi */
body{
    margin:0;
    font-family:'Segoe UI';
    background:linear-gradient(135deg,#1f1c2c,#928dab);
}


/* Navbar tasarimi */
.navbar{
    background:rgba(255,255,255,0.1);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
}


/* Ana container */
.container{
    width:85%;
    margin:auto;
    margin-top:30px;
}


/* Filtre kutusu */
.filter-box{
    background:rgba(255,255,255,0.15);
    padding:20px;
    border-radius:20px;
    display:flex;
    gap:10px;
}


/* Input ve select tasarimi */
.filter-box input,
.filter-box select{
    padding:10px;
    border-radius:10px;
    border:none;
}


/* Genel buton tasarimi */
button{
    background:linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;
    border:none;
    padding:10px 20px;
    border-radius:10px;
}


/* Icerik karti */
.card{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-top:20px;
}


/* Type badge ortak tasarimi */
.type-badge{
    padding:5px 10px;
    border-radius:10px;
    color:white;
    font-size:12px;
}


/* Note tipi */
.note{
    background:#3498db;
}


/* Video tipi */
.video{
    background:#e74c3c;
}


/* Question tipi */
.question{
    background:#9b59b6;
}


/* Genel button link tasarimi */
.btn{
    padding:6px 10px;
    border-radius:8px;
    color:white;
    text-decoration:none;
}


/* Silme butonu */
.delete{
    background:red;
}


/* Duzenleme butonu */
.edit{
    background:orange;
    margin-left:10px;
}


/* Modal arka plani */
#modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    justify-content:center;
    align-items:center;
}


/* Modal kutusu */
.modal-box{
    background:white;
    padding:25px;
    border-radius:20px;
    width:350px;
}


/* Modal form yapisi */
.modal-form{
    display:flex;
    flex-direction:column;
    gap:10px;
}


/* Modal input tasarimi */
.modal-form input,
.modal-form textarea,
.modal-form select{
    padding:10px;
    border-radius:10px;
    border:1px solid #ddd;
}


/* Kaydet butonu */
.save-btn{
    background: linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;
    border:none;
    padding:10px;
    border-radius:10px;
}


/* Modal kapatma butonu */
.close-btn{
    float:right;
    cursor:pointer;
}

</style>

</head>

<body>

<!-- Navbar -->
<div class="navbar">

<div>

📚 EduPool |

<!-- Ana sayfa linki -->
<a href="list.php" style="color:white;">Ana Sayfa</a> |

<!-- Projeler linki -->
<a href="projects.php" style="color:white;">Projeler & Odevler </a>

</div>

<div>

<!-- Kullanici adi -->
👤 <?php echo $user['username']; ?> |

<!-- Cikis linki -->
<a href="logout.php" style="color:white;">Cikis</a>

</div>
</div>



<div class="container">

<!-- ======================
     FILTRE FORMU
====================== -->

<form class="filter-box" method="GET" style="display:flex; align-items:center; gap:10px;">


<!-- Ders adi arama -->
<input type="text"
       name="lesson"
       placeholder="Ders adi..."
       value="<?php echo $lesson; ?>">


<!-- Tur secimi -->
<select name="type">

<option value="">Tur</option>

<option value="note"
<?php if($type=='note') echo 'selected'; ?>>
Not
</option>

<option value="video"
<?php if($type=='video') echo 'selected'; ?>>
Video
</option>

<option value="question"
<?php if($type=='question') echo 'selected'; ?>>
Soru
</option>

</select>



<!-- Filtreleme butonu -->
<button style="
background: linear-gradient(45deg,#ff6a00,#ee0979);
color:white;
padding:10px 20px;
border:none;
border-radius:10px;
cursor:pointer;
margin-left:auto;
">

🔎 Ara & Filtrele

</button>

</form>



<!-- Modal acma butonu -->
<div style="text-align:right;margin-top:20px;">

<button onclick="openModal()">➕ Icerik Ekle</button>

</div>



<!-- ======================
     SONUC YOKSA
====================== -->

<?php if($result->num_rows == 0){ ?>

<div style="
background:white;
padding:30px;
border-radius:20px;
text-align:center;
margin-top:20px;
">

<h3>Icerik bulunamadi!!!</h3>

</div>

<?php } else { ?>


<!-- ======================
     ICERIKLERI LISTELEME
====================== -->

<?php while($row = $result->fetch_assoc()){ ?>

<div class="card">


<!-- Type badge -->
<?php if($row['type']=='note'){ ?>

<span class="type-badge note">📝 Not</span>

<?php } elseif($row['type']=='video'){ ?>

<span class="type-badge video">🎥 Video</span>

<?php } else { ?>

<span class="type-badge question">❓ Soru</span>

<?php } ?>


<br><br>


<!-- Ders bilgisi -->
<b>📘 Ders:</b>
<?php echo $row['lesson_name'] ?? "Genel"; ?>

<br><br>


<!-- Aciklama -->
<b>Aciklama:</b><br>

<?php echo $row['description'] ?? "-"; ?>

<br><br>



<?php

// file_path degeri alinir
$file = $row['file_path'];

?>



<!-- Dosya varsa -->
<?php if(!empty($file)){ ?>


    <!-- Video ise -->
    <?php if($row['type']=='video'){ ?>


        <!-- Video linki -->
        <a href="<?php echo $file; ?>" target="_blank"
        style="background:#e74c3c;color:white;padding:8px 12px;border-radius:8px;text-decoration:none;">

        ▶ Videoyu Ac

        </a>

    <?php } else { ?>


        <?php

        // Dosya uzantisi alinir
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        ?>


        <!-- PDF ise -->
        <?php if($ext=='pdf'){ ?>

            <a href="<?php echo $file; ?>" target="_blank">📄 PDF Ac</a>


        <!-- Resim ise -->
        <?php } elseif(in_array($ext,['jpg','jpeg','png','gif'])){ ?>

            <img src="<?php echo $file; ?>" style="width:100%;border-radius:10px;">


        <!-- Diger dosyalar -->
        <?php } else { ?>

            <a href="<?php echo $file; ?>" target="_blank">📎 Dosya indir</a>

        <?php } ?>

    <?php } ?>

<?php } ?>


<br><br>


<!-- Sil butonu -->
<a class="btn delete"
href="delete.php?id=<?php echo $row['id']; ?>">
Sil
</a>


<!-- Duzenle butonu -->
<a class="btn edit"
href="edit.php?id=<?php echo $row['id']; ?>">
Duzenle
</a>

</div>

<?php } ?>

<?php } ?>

</div>



<!-- ======================
     MODAL
====================== -->

<div id="modal">

<div class="modal-box">


<!-- Modal kapatma -->
<span onclick="closeModal()" class="close-btn">✖</span>


<h3>Yeni Icerik</h3>


<!--
enctype="multipart/form-data"
Dosya yuklemek icin gereklidir
-->
<form action="add_content.php"
      method="POST"
      enctype="multipart/form-data"
      class="modal-form">


<!-- Ders adi -->
<input type="text"
       name="lesson_name"
       placeholder="Ders adi"
       required>


<!-- Aciklama -->
<textarea name="description"
placeholder="Aciklama"></textarea>


<!-- Type secimi -->
<select name="type">

<option value="note">Not</option>
<option value="video">Video</option>
<option value="question">Soru</option>

</select>


<!-- Video linki -->
<input type="text"
       name="video_url"
       placeholder="Video linki (YouTube vs)">



<!-- Dosya yukleme -->
<input type="file" name="file">


<!-- Kaydet butonu -->
<button type="submit" class="save-btn">
Kaydet
</button>

</form>

</div>
</div>



<script>

// Modal acma fonksiyonu
function openModal(){
    document.getElementById("modal").style.display="flex";
}


// Modal kapatma fonksiyonu
function closeModal(){
    document.getElementById("modal").style.display="none";
}

</script>

</body>
</html>