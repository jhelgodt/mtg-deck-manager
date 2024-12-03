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
    <link rel="stylesheet" href="../assets/css/style.css">
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
        <th>Image</th>
        <th title="Mana Cost and CMC">Mana (CMC)</th>
        <th>Type</th>
        <th>Set/Rarity</th>
        <th>Text</th>
        <th>Color/Produced</th>
        <th>Power/Toughness</th>
        <th>Price</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($cards as $card): ?>
        <tr>
            <td><?= htmlspecialchars($card['card_id'] ?? '') ?></td>
            <td><?= htmlspecialchars($card['card_name'] ?? '') ?></td>
            <td>
                <?php if (!empty($card['image_uri'])): ?>
                    <img class="card-image" src="<?= htmlspecialchars($card['image_uri']) ?>" alt="Card Image" width="100">
                <?php else: ?>
                    No Image
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($card['mana_cost'] ?? '') ?> (<?= htmlspecialchars($card['cmc'] ?? '') ?>)</td>
            <td><?= htmlspecialchars($card['type_line'] ?? '') ?></td>
            <td><?= htmlspecialchars($card['set_name'] ?? '') ?> (<?= htmlspecialchars($card['rarity'] ?? '') ?>)</td>
            <td><?= htmlspecialchars($card['oracle_text'] ?? '') ?></td>
            <td><?= htmlspecialchars($card['color_identity'] ?? '') ?> (<?= htmlspecialchars($card['produced_mana'] ?? '') ?>)</td>
            <td><?= htmlspecialchars($card['power'] ?? '') ?>/<?= htmlspecialchars($card['toughness'] ?? '') ?></td>
            <td>USD: <?= htmlspecialchars($card['usd_price'] ?? '0.00') ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>
    </table>

    <a href="../index.php">Back to Deck Manager</a>
</body>
</html>