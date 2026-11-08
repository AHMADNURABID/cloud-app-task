<?php
$host = getenv('DB_HOST') ?: 'database';
$db   = getenv('DB_DATABASE') ?: 'my_db';
$user = getenv('DB_USERNAME') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'secret';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS notes (id INT AUTO_INCREMENT PRIMARY KEY, content VARCHAR(255))");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['note'])) {
        $stmt = $pdo->prepare("INSERT INTO notes (content) VALUES (?)");
        $stmt->execute([$_POST['note']]);
    }

    $notes = $pdo->query("SELECT * FROM notes")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cloud Comp Task App</title>
    <style>
        body { font-family: sans-serif; padding: 40px; background: #f4f4f9; }
        .card { background: white; padding: 20px; border-radius: 8px; max-width: 500px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        input[type=text] { width: 75%; padding: 8px; }
        button { padding: 8px 15px; background: #007bff; color: white; border: none; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Tugas Komputasi Awan - Docker VM</h2>
        <p>Status Database: <strong><?php echo isset($error) ? "Error: $error" : "Terhubung ke MySQL!"; ?></strong></p>
        <form method="POST">
            <input type="text" name="note" placeholder="Tulis data pengujian..." required>
            <button type="submit">Tambah</button>
        </form>
        <h3>Data Tersimpan:</h3>
        <ul>
            <?php foreach ($notes ?? [] as $n): ?>
                <li><?php echo htmlspecialchars($n['content']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>