<?php
// Databasanslutningsdetaljer
$servername = "mariadb";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

try {
    // Skapa en PDO-anslutning
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kontrollera om tabellen finns, annars skapa den
    $conn->exec("
        CREATE TABLE IF NOT EXISTS test_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Om formuläret skickas, lägg till ett nytt värde i tabellen
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["name"])) {
        $name = htmlspecialchars($_POST["name"]); // Rensa användarinmatning för säkerhet
        $stmt = $conn->prepare("INSERT INTO test_table (name) VALUES (:name)");
        $stmt->bindParam(':name', $name);
        $stmt->execute();
        echo "Added '$name' to the table.<br>";
    }

    // Hämta alla värden från tabellen för att visa dem
    $stmt = $conn->query("SELECT * FROM test_table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Hantera anslutningsfel
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP PDO Test</title>
</head>
<body>
    <h1>PHP + MariaDB Example</h1>

    <!-- Formulär för att lägga till ett nytt namn till tabellen -->
    <form method="POST" action="">
        <label for="name">Enter a name:</label>
        <input type="text" id="name" name="name" required>
        <button type="submit">Add to Table</button>
    </form>

    <h2>Data in 'test_table'</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>