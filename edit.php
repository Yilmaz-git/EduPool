<?php

// Veritabanina baglanir
$conn = new mysqli("localhost", "root", "", "edu_pool");


// Eger baglanti basarisizsa program durur
if ($conn->connect_error) die("Baglanti hatasi");



// ======================
// URL'DEN ID ALMA
// ======================

// URL'den gelen id degeri alinir
// Ornek:
// edit.php?id=5
$id = $_GET['id'];



// ======================
// VERITABANINDAN ICERIGI CEKME
// ======================

// contents tablosundan id'ye gore veri secilir
$sql = "SELECT * FROM contents WHERE id = $id";


// SQL sorgusu calistirilir
$result = $conn->query($sql);


// Gelen veri dizi seklinde alinir
$row = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>

    <!-- Sayfa basligi -->
    <title>Guncelle</title>

    <style>

        /* Sayfanin genel gorunumu */
        body {
            font-family: Arial;
            background:#f5f5f5;
            padding:20px;
        }

        /* Form kutusu */
        form {
            background:white;
            padding:15px;
            border-radius:10px;
            width:300px;
        }

        /* Input ve select stilleri */
        input, select {
            width:100%;
            margin:5px 0;
            padding:8px;
        }

        /* Buton gorunumu */
        button {
            background:green;
            color:white;
            padding:8px;
            border:none;
            border-radius:5px;
        }

    </style>

</head>

<body>

<!-- Sayfa basligi -->
<h2>Icerigi Guncelle</h2>


<!--
Form update.php dosyasina gonderilir
method="POST" -> veriler gizli sekilde gider
-->
<form action="update.php" method="POST">


    <!--
    Gizli input:
    Kullanici gormez ama veri gonderilir
    -->
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">


    <!-- Baslik alani -->
    Baslik:

    <!-- Veritabanindaki mevcut title degeri input icine yazilir -->
    <input type="text" name="title" value="<?php echo $row['title']; ?>">


    <!-- Aciklama alani -->
    Aciklama:

    <!-- description degeri gosterilir -->
    <input type="text" name="description" value="<?php echo $row['description']; ?>">


    <!-- Tur secme alani -->
    Tur:

    <select name="type">

        <!--
        Eger type = note ise
        selected eklenir ve secili gelir
        -->
        <option value="note"
        <?php if($row['type']=='note') echo 'selected'; ?>>
        Not
        </option>


        <!-- Video secenegi -->
        <option value="video"
        <?php if($row['type']=='video') echo 'selected'; ?>>
        Video
        </option>


        <!-- Soru secenegi -->
        <option value="question"
        <?php if($row['type']=='question') echo 'selected'; ?>>
        Soru
        </option>

    </select>



    <!-- lesson_id alani -->
    Ders ID:

    <!-- Mevcut lesson_id degeri gosterilir -->
    <input type="number"
           name="lesson_id"
           value="<?php echo $row['lesson_id']; ?>">


    <!-- Guncelleme butonu -->
    <button type="submit">Guncelle</button>

</form>

</body>
</html>