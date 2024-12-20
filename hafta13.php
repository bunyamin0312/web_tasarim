<?php
$host = 'localhost';
$dbname = 'test';
$username = 'root'; // Bu kısmı, yerel veya uzaktaki veritabanı ayarınıza göre değiştirin
$password = '';

try {
    // Veritabanı bağlantısı
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// 1. Form - Kişi bilgilerini kaydetme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_person'])) {
    $ad = $_POST['ad'];
    $soyad = $_POST['soyad'];
    $email = $_POST['email'];

    $sql = "INSERT INTO kisi (ad, soyad, email) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ad, $soyad, $email]);
}

// 2. Form - Kişi arama
$search_result = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_search'])) {
    $search_name = $_POST['search_name'];

    $sql = "SELECT soyad, email FROM kisi WHERE ad = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_name]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $search_result = "Soyad: " . $result['soyad'] . " | Email: " . $result['email'];
    } else {
        $search_result = "Kişi bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişi Ekleme ve Arama</title>
</head>
<body>
    <h1>Kişi Ekleme</h1>
    <form method="POST">
        <label for="ad">Ad:</label>
        <input type="text" id="ad" name="ad" required><br>
        
        <label for="soyad">Soyad:</label>
        <input type="text" id="soyad" name="soyad" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <button type="submit" name="submit_person">Kişi Ekle</button>
    </form>

    <hr>

    <h1>Kişi Arama</h1>
    <form method="POST">
        <label for="search_name">Adı Girin:</label>
        <input type="text" id="search_name" name="search_name" required><br>

        <button type="submit" name="submit_search">Bul</button>
    </form>

    <p><strong>Sonuç: </strong><?php echo $search_result; ?></p>
</body>
</html>

