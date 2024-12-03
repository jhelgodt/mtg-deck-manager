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
    <table border="1">
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Mana Cost</th>
                <th>Type Line</th>
                <th>Oracle Text</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cards as $card): ?>
                <tr>
                    <td><?= htmlspecialchars($card['card_name']) ?></td>
                    <td><?= htmlspecialchars($card['mana_cost']) ?></td>
                    <td><?= htmlspecialchars($card['type_line']) ?></td>
                    <td><?= htmlspecialchars($card['oracle_text']) ?></td>
                    <td>
                        <a href="edit_card.php?card_id=<?= $card['card_id'] ?>">Edit</a>
                        <a href="delete_card.php?card_id=<?= $card['card_id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php">Back to Deck Manager</a>
</body>
</html>