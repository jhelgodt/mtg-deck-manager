<?php
require __DIR__ . '/../../db/connect.php'; 

try {
    // Hämta alla kort från tabellen
    $stmt = $conn->query("SELECT * FROM cards");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cards: " . $e->getMessage());
}
?>
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
                <td><?= htmlspecialchars($row['mana_cost'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['type_line'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['keywords'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['set_name'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['rarity'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($row['oracle_text'] ?? 'N/A') ?></td>
                <td>
                    <?php if (!empty($row['image_uri'])): ?>
                        <img src="<?= htmlspecialchars($row['image_uri']) ?>" alt="Card Image" style="max-width: 100px;">
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['last_updated'] ?? 'N/A') ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="10">No cards found in the database.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>