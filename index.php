<?php
// Inkludera databasanslutningen
require 'db/connect.php';

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
    <h2>Cards in Database</h2>

    <!-- Visa tabellen med kort -->
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
                    <td colspan="7">No cards found in the database.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>