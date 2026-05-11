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

body{
    margin:0;
    font-family:'Segoe UI';
    background:linear-gradient(135deg,#1f1c2c,#928dab);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    background:white;
    padding:35px;
    border-radius:25px;
    width:420px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
}

h2{
    margin-top:0;
    margin-bottom:25px;
}

input,
select{
    width:100%;
    padding:14px;
    margin-bottom:15px;
    border-radius:12px;
    border:1px solid #ddd;
    font-size:15px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:linear-gradient(45deg,#ff6a00,#ee0979);
    color:white;
    font-size:16px;
    cursor:pointer;
}

    </style>


</head>

<body>

<div class="box">

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

    <!-- Guncelleme butonu -->
    <button type="submit">Guncelle</button>

</form>
</div>

</body>
</html>