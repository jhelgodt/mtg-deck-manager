<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen

// Hämta alla kort från databasen
try {
    $sql = "SELECT * FROM cards ORDER BY card_name ASC";
    $stmt = $conn->query($sql);
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cards: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Cards</title>
</head>
<body>
    <h1>All Cards</h1>

    <!-- Tabell med alla kort -->
    <table>
        <thead>
            <tr>
                <th>Card ID</th>
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
            <?php foreach ($cards as $card): ?>
                <tr>
                    <td><?= htmlspecialchars($card['card_id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['card_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['mana_cost'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['type_line'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['keywords'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['set_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['rarity'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['oracle_text'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['artist'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['color_identity'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['produced_mana'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['cmc'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['games'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['reserved'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['foil'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['nonfoil'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['oversized'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['promo'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['reprint'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['usd_price'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['eur_price'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['tix_price'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['toughness'] ?? '') ?></td>
                    <td><?= htmlspecialchars($card['power'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($card['image_uri'])): ?>
                            <img src="<?= htmlspecialchars($card['image_uri']) ?>" alt="Card Image" width="150">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($card['last_updated'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="../index.php">Back to Deck Manager</a>
</body>
</html>