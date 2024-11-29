<?php
require __DIR__ . '/../../db/connect.php'; 

// Hämta alla kort från databasen
try {
    $stmt = $conn->query("SELECT * FROM cards");
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching cards. Please try again later.</p>";
    $cards = [];
}

// Hämta alla decks från databasen
try {
    $stmt = $conn->query("SELECT * FROM decks");
    $decks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching decks. Please try again later.</p>";
    $decks = [];
}
?>

<h2>All Cards</h2>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Card Name</th>
            <th>Mana Cost</th>
            <th>Type Line</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($cards)): ?>
        <?php foreach ($cards as $card): ?>
            <tr>
                <td><?= htmlspecialchars($card['card_name']) ?></td>
                <td><?= htmlspecialchars($card['mana_cost'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars($card['type_line'] ?? 'N/A') ?></td>
                <td>
                    <!-- Formulär för att lägga till kort i en lek -->
                    <form action="../decks/add_card.php" method="POST">
                        <input type="hidden" name="card_id" value="<?= htmlspecialchars($card['id']) ?>">
                        <label for="deck_id">Deck:</label>
                        <select name="deck_id" required>
                            <?php foreach ($decks as $deck): ?>
                                <option value="<?= htmlspecialchars($deck['id']) ?>">
                                    <?= htmlspecialchars($deck['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" min="1" value="1" required>
                        <label for="status">Status:</label>
                        <select name="status" required>
                            <option value="Active">Active</option>
                            <option value="Considering">Considering</option>
                        </select>
                        <button type="submit">Add to Deck</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No cards found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>