<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen

$deckId = $_GET['deck_id'] ?? null;
if (!$deckId) {
    die("Deck ID is required.");
}

// Hämta däcket och dess kort
try {
    // Hämta däcket
    $sql = "SELECT * FROM decks WHERE deck_id = :deck_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['deck_id' => $deckId]);
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$deck) {
        die("Deck not found.");
    }

    // Hämta kort relaterade till däcket
    $sql = "SELECT c.*, dc.quantity_active, dc.quantity_considering 
            FROM deck_cards dc
            JOIN cards c ON dc.card_id = c.card_id
            WHERE dc.deck_id = :deck_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['deck_id' => $deckId]);
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Hämta alla kort (för att lägga till nya)
    $sql = "SELECT * FROM cards";
    $allCardsStmt = $conn->query($sql);
    $allCards = $allCardsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching deck or cards: " . $e->getMessage());
}

// Hantera inlämning för att lägga till kort till däcket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardId = $_POST['card_id'] ?? null;
    $quantityActive = $_POST['quantity_active'] ?? 0;
    $quantityConsidering = $_POST['quantity_considering'] ?? 0;

    if ($cardId) {
        try {
            $sql = "INSERT INTO deck_cards (deck_id, card_id, quantity_active, quantity_considering)
                    VALUES (:deck_id, :card_id, :quantity_active, :quantity_considering)
                    ON DUPLICATE KEY UPDATE 
                    quantity_active = quantity_active + :quantity_active,
                    quantity_considering = quantity_considering + :quantity_considering";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'deck_id' => $deckId,
                'card_id' => $cardId,
                'quantity_active' => $quantityActive,
                'quantity_considering' => $quantityConsidering
            ]);
            header("Location: deck.php?deck_id=$deckId");
            exit;
        } catch (PDOException $e) {
            die("Error adding card to deck: " . $e->getMessage());
        }
    } else {
        echo "Card ID is required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck: <?= htmlspecialchars($deck['deck_name']) ?></title>
</head>
<body>
    <h1>Deck: <?= htmlspecialchars($deck['deck_name']) ?></h1>

    <!-- Visa kort i däcket -->
    <h2>Cards in Deck</h2>
    <ul>
        <?php foreach ($cards as $card): ?>
            <li>
                <?= htmlspecialchars($card['card_name']) ?> 
                (Active: <?= $card['quantity_active'] ?>, Considering: <?= $card['quantity_considering'] ?>)
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Formulär för att lägga till kort -->
    <h2>Add Card to Deck</h2>
    <form method="POST">
        <label for="card_id">Select Card:</label>
        <select id="card_id" name="card_id" required>
            <?php foreach ($allCards as $card): ?>
                <option value="<?= $card['card_id'] ?>"><?= htmlspecialchars($card['card_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="quantity_active">Quantity Active:</label>
        <input type="number" id="quantity_active" name="quantity_active" value="0" min="0">
        <br>
        <label for="quantity_considering">Quantity Considering:</label>
        <input type="number" id="quantity_considering" name="quantity_considering" value="0" min="0">
        <br>
        <button type="submit">Add to Deck</button>
    </form>

    <a href="index.php">Back to Decks</a>
</body>
</html>