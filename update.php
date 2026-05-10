<?php
$conn = new mysqli("localhost", "root", "", "edu_pool"); //mysqli sinifini kullanarak edu_pool veritabanina baglan
if ($conn->connect_error) die("Bağlantı hatası"); // baglanti sirasinda sorun olusursa durdur ve hata mesaji bas

$id = $_POST['id']; // Formdan (POST metoduyla) gönderilen 'id' değerini al
$title = $_POST['title']; // Formdan (POST metoduyla) gönderilen 'baslik' değerini al
$description = $_POST['description']; // Formdan (POST metoduyla) gönderilen 'aciklama' değerini al
$type = $_POST['type']; // Formdan (POST metoduyla) gönderilen 'tür' değerini al
$lesson_id = $_POST['lesson_id']; // Formdaki 'lesson_id' (ders numarası) kutusuna yazılan değeri al

$sql = "UPDATE contents 
SET title='$title', description='$description', type='$type', lesson_id='$lesson_id' 
WHERE id=$id"; // contents tablosunda id'ye ait baslik, aciklama, tur, ders_id sutunlari formdan gelen yeni degiskenlerle guncelle

//$sql icindeki SQL komutunu veritabanina gonderip calistirarak
if ($conn->query($sql) === TRUE) { // Sorguyu veritabanina gonder
    header("Location: list.php"); // Eger veritabani basariyla guncellendiyse kullaniciyi liste sayfasina (list.php) gonder
} else {
    echo "Hata oluştu"; //sorun olustuysa hata olustu mesajini yaz
}
?>