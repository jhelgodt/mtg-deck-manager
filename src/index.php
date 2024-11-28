<?php
// Inkludera databasanslutningen
require '../db/connect.php';

// Hantera inmatning från formuläret
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['new_card_name'])) {
    $newCardName = htmlspecialchars($_POST['new_card_name']); // Rensa inmatningen för säkerhet

    try {
        // Infoga ett nytt kort i databasen
        $stmt = $conn->prepare("INSERT INTO cards (card_name) VALUES (:card_name)");
        $stmt->bindParam(':card_name', $newCardName);
        $stmt->execute();
        echo "<p>Card '$newCardName' has been added successfully.</p>";
    } catch (PDOException $e) {
        echo "<p>Error adding card: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

try {
    // Hämta alla kort från tabellen
    $stmt = $conn->query("SELECT * FROM cards");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cards: " . $e->getMessage());
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
    <h2>Add a New Card</h2>
    <form method="POST" action="">
        <label for="new_card_name">Card Name:</label>
        <input type="text" id="new_card_name" name="new_card_name" required>
        <button type="submit">Add Card</button>
    </form>

    <!-- Visa tabellen med kort -->
    <h2>Cards in Database</h2>
    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Card Name</th>
                <th>Mana Cost</th>
                <th>Type Line</th>
                <th>Keywords</th>
                <th>Set Name</th>
                <th>Rarity</th>
                <th>Oracle Text</th>
                <th>Artist</th>
                <th>Color Identity</th>
                <th>Produced Mana</th>
                <th>CMC</th>
                <th>Games</th>
                <th>Reserved</th>
                <th>Foil</th>
                <th>Nonfoil</th>
                <th>Oversized</th>
                <th>Promo</th>
                <th>Reprint</th>
                <th>USD Price</th>
                <th>EUR Price</th>
                <th>TIX Price</th>
                <th>Toughness</th>
                <th>Power</th>
                <th>Image</th>
                <th>Last Updated</th>
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
                    <td><?= htmlspecialchars($row['keywords'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['set_name']) ?></td>
                    <td><?= htmlspecialchars($row['rarity']) ?></td>
                    <td><?= htmlspecialchars($row['oracle_text'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['artist'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['color_identity'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['produced_mana'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['cmc'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['games'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['reserved'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['foil'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['nonfoil'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['oversized'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['promo'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['reprint'] ? 'Yes' : 'No') ?></td>
                    <td><?= htmlspecialchars($row['usd_price'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['eur_price'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['tix_price'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['toughness'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($row['power'] ?? 'N/A') ?></td>
                    <td>
                        <?php if (!empty($row['image_uri'])): ?>
                            <img src="<?= htmlspecialchars($row['image_uri']) ?>" alt="Card Image" style="max-width: 100px;">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['last_updated'] ?? 'N/A') ?></td>
                    <td>
                        <!-- Lägg till en knapp som skickar kortnamnet till fetch_card.php -->
                        <form action="fetch_card.php" method="POST" style="display:inline;">
                            <input type="hidden" name="card_name" value="<?= htmlspecialchars($row['card_name']) ?>">
                            <button type="submit">Fetch Data</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="25">No cards found in the database.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</body>
</html>