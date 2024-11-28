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
        CREATE TABLE IF NOT EXISTS cards (
            id INT AUTO_INCREMENT PRIMARY KEY,
            card_name VARCHAR(255) NOT NULL,
            mana_cost VARCHAR(50),
            type_line VARCHAR(255),
            set_name VARCHAR(100),
            rarity VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Om formuläret skickas, lägg till ett nytt kort i tabellen
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["card_name"])) {
        // Rensa och hämta användarinmatning
        $card_name = htmlspecialchars($_POST["card_name"]);
        $mana_cost = htmlspecialchars($_POST["mana_cost"]);
        $type_line = htmlspecialchars($_POST["type_line"]);
        $set_name = htmlspecialchars($_POST["set_name"]);
        $rarity = htmlspecialchars($_POST["rarity"]);

        // Förbered SQL-frågan för att lägga till kortet
        $stmt = $conn->prepare("
            INSERT INTO cards (card_name, mana_cost, type_line, set_name, rarity)
            VALUES (:card_name, :mana_cost, :type_line, :set_name, :rarity)
        ");
        $stmt->bindParam(':card_name', $card_name);
        $stmt->bindParam(':mana_cost', $mana_cost);
        $stmt->bindParam(':type_line', $type_line);
        $stmt->bindParam(':set_name', $set_name);
        $stmt->bindParam(':rarity', $rarity);
        $stmt->execute();

        echo "Added card '$card_name' to the table.<br>";
    }

    // Hämta alla kort från tabellen
    $stmt = $conn->query("SELECT * FROM cards");
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
    <title>Magic the Gathering Deck Builder</title>
</head>
<body>
    <h1>Magic the Gathering: Deck Builder</h1>

    <!-- Formulär för att lägga till ett nytt kort -->
    <form method="POST" action="">
        <label for="card_name">Card Name:</label>
        <input type="text" id="card_name" name="card_name" required><br>

        <label for="mana_cost">Mana Cost:</label>
        <input type="text" id="mana_cost" name="mana_cost"><br>

        <label for="type_line">Type Line:</label>
        <input type="text" id="type_line" name="type_line"><br>

        <label for="set_name">Set Name:</label>
        <input type="text" id="set_name" name="set_name"><br>

        <label for="rarity">Rarity:</label>
        <input type="text" id="rarity" name="rarity"><br>

        <button type="submit">Add Card</button>
    </form>

    <h2>Cards in Database</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Card Name</th>
                <th>Mana Cost</th>
                <th>Type Line</th>
                <th>Set Name</th>
                <th>Rarity</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['card_name']) ?></td>
                        <td><?= htmlspecialchars($row['mana_cost']) ?></td>
                        <td><?= htmlspecialchars($row['type_line']) ?></td>
                        <td><?= htmlspecialchars($row['set_name']) ?></td>
                        <td><?= htmlspecialchars($row['rarity']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No cards available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>