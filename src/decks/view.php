<?php
require __DIR__ . '/../../db/connect.php';

// Kontrollera att ett deck_id skickas via GET
if (!isset($_GET['deck_id']) || empty($_GET['deck_id'])) {
    echo "<p>No deck selected.</p>";
    exit;
}

$deckId = intval($_GET['deck_id']); // Hämta och säkerställ att deck_id är ett heltal

try {
    // Hämta deckets namn
    $stmt = $conn->prepare("SELECT deck_name FROM decks WHERE id = :deck_id");
    $stmt->bindParam(':deck_id', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deck) {
        echo "<p>Deck not found.</p>";
        exit;
    }

    // Hämta alla kort i decket
    $stmt = $conn->prepare("
        SELECT c.card_name, dc.active_quantity, dc.considering_quantity
        FROM deck_cards dc
        JOIN cards c ON dc.card_id = c.id
        WHERE dc.deck_id = :deck_id
    ");
    $stmt->bindParam(':deck_id', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching deck details: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}
?>

<h2>Deck: <?= htmlspecialchars($deck['deck_name']) ?></h2>
<nav>
    <a href="../list.php">Back to Deck List</a> |
    <a href="../../index.php">Home</a>
</nav>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Card Name</th>
            <th>Active Quantity</th>
            <th>Considering Quantity</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($cards)): ?>
            <?php foreach ($cards as $card): ?>
                <tr>
                    <td><?= htmlspecialchars($card['card_name']) ?></td>
                    <td><?= htmlspecialchars($card['active_quantity']) ?></td>
                    <td><?= htmlspecialchars($card['considering_quantity']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No cards in this deck.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>