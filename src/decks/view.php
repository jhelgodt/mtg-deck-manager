<?php
require __DIR__ . '/../../db/connect.php';

if (!isset($_GET['deck_id'])) {
    echo "<p>No deck ID provided.</p>";
    exit;
}

$deckId = intval($_GET['deck_id']);

try {
    // Hämta leken
    $stmt = $conn->prepare("SELECT * FROM decks WHERE id = :id");
    $stmt->bindParam(':id', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deck) {
        echo "<p>Deck not found.</p>";
        exit;
    }

    // Hämta kort från deck_cards
    $stmt = $conn->prepare("
        SELECT 
            c.card_name, 
            c.mana_cost, 
            c.type_line, 
            dc.quantity_active, 
            dc.quantity_considering
        FROM deck_cards dc
        JOIN cards c ON dc.card_id = c.id
        WHERE dc.deck_id = :deck_id
        ORDER BY c.card_name ASC
    ");
    $stmt->bindParam(':deck_id', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching deck details: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Visa leken och korten
?>
<h2>Deck: <?= htmlspecialchars($deck['name']) ?></h2>
<p>Created at: <?= htmlspecialchars($deck['created_at']) ?></p>

<h3>Active Cards</h3>
<ul>
    <?php foreach ($cards as $card): ?>
        <?php if ($card['quantity_active'] > 0): ?>
            <li>
                <?= htmlspecialchars($card['card_name']) ?> 
                (<?= htmlspecialchars($card['quantity_active']) ?> copies)
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<h3>Considering Cards</h3>
<ul>
    <?php foreach ($cards as $card): ?>
        <?php if ($card['quantity_considering'] > 0): ?>
            <li>
                <?= htmlspecialchars($card['card_name']) ?> 
                (<?= htmlspecialchars($card['quantity_considering']) ?> copies)
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>