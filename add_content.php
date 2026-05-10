<?php
session_start(); // Session baslatilir (kullanici bilgilerini tutmak icin)


// Veritabanina baglanir
$conn = new mysqli("localhost","root","","edu_pool");

// Eger baglanti basarisiz olursa programi durdurur
if ($conn->connect_error) die("DB baglanti hatasi");


// ======================
// FORM VERILERINI ALMA
// ======================

// lesson_name alanini alir, bosluklari temizler
$lesson_name = trim($_POST['lesson_name'] ?? '');

// description alanini alir
$description = trim($_POST['description'] ?? '');

// icerik tipi alinir (note/video gibi)
// eger gelmezse varsayilan olarak "note" atanir
$type = $_POST['type'] ?? 'note';

// video linki alinir
$video_url = trim($_POST['video_url'] ?? '');

// sessiondaki kullanici id alinir
// session yoksa test amacli 1 kullanilir
$user_id = $_SESSION['user_id'] ?? 1;



// ======================
// DERS ADI BOSSA OTOMATIK DOLDUR
// ======================

// Eger lesson_name bossa
if ($lesson_name === '') {

    // description bos degilse ilk 20 karakterini al
    // description da bossa "Genel" yaz
    $lesson_name = $description !== '' ? substr($description, 0, 20) : "Genel";
}



// ======================
// LESSON TABLOSUNDA DERS VAR MI KONTROLU
// ======================

// lessons tablosunda ayni isimde ders var mi kontrol edilir
$stmt = $conn->prepare("SELECT id FROM lessons WHERE lesson_name=?");

// ? yerine lesson_name degiskeni baglanir
// s = string veri tipi
$stmt->bind_param("s",$lesson_name);

// sorgu calistirilir
$stmt->execute();

// sonuc alinir
$res = $stmt->get_result();


// Eger ders varsa
if($res->num_rows > 0){

    // mevcut dersin id'si alinir
    $lesson_id = $res->fetch_assoc()['id'];

} else {

    // Ders yoksa yeni ders olusturulur
    $stmt = $conn->prepare("INSERT INTO lessons (lesson_name) VALUES (?)");

    // lesson_name parametresi baglanir
    $stmt->bind_param("s",$lesson_name);

    // insert islemi calistirilir
    $stmt->execute();

    // eklenen dersin id'si alinir
    $lesson_id = $conn->insert_id;
}



// ======================
// DOSYA / VIDEO AYRIMI
// ======================

// file_path baslangicta bos atanir
$file_path = "";



/* ======================
    VIDEO ONCELIKLI
====================== */

// Eger icerik tipi video ise
if($type === "video"){


    // video linki bos degilse
    if($video_url !== ""){

        // file_path icine video linki kaydedilir
        $file_path = $video_url;
    }

} else {

    /* ======================
        NORMAL DOSYA UPLOAD
    ====================== */

    // Eger dosya geldiyse ve hata olusmadiysa
    if(isset($_FILES['file']) && $_FILES['file']['error'] === 0){


        // uploads klasurunun yolu belirlenir
        $uploadDir = __DIR__ . "/uploads/";


        // uploads klasoru yoksa olusturulur
        if(!is_dir($uploadDir)){

            // 0777 = tum izinler
            // true = ic ice klasorleri de olustur
            mkdir($uploadDir, 0777, true);
        }


        // Dosya adina zaman eklenir
        // Boylece ayni isimli dosyalar cakismaz
        $fileName = time() . "_" . basename($_FILES['file']['name']);


        // Dosyanin kaydedilecegi tam yol olusturulur
        $targetPath = $uploadDir . $fileName;


        // Dosya gecici klasorden hedef klasore tasinir
        if(move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)){

            // Veritabanina kaydedilecek relative path olusturulur
            $file_path = "uploads/" . $fileName;
        }
    }
}



// ======================
// DEBUG AMACLI KONTROL
// ======================

// file_path degerini ekrana basar
// gelistirme sirasinda kontrol icin kullanilabilir
// var_dump($file_path); exit();




// ======================
// CONTENTS TABLOSUNA EKLEME
// ======================

// contents tablosuna veri eklemek icin sorgu hazirlanir
$stmt = $conn->prepare("
INSERT INTO contents (description, type, lesson_id, user_id, file_path)
VALUES (?, ?, ?, ?, ?)
");


// Parametreler baglanir
// s = string
// i = integer
$stmt->bind_param("ssiis", $description, $type, $lesson_id, $user_id, $file_path);


// INSERT sorgusu calistirilir
$stmt->execute();



// ======================
// SAYFA YÖNLENDİRME
// ======================

// islem bittikten sonra list.php sayfasina gider
header("Location: list.php");

// script tamamen durdurulur
exit();

?>