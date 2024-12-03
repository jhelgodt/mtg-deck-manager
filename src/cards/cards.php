<?php
require_once __DIR__ . '/../../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen

$deckId = $_GET['deck_id'] ?? null;
if (!$deckId) {
    die("Deck ID is required.");
}

// Hämta däcket och dess kort
try {
    $sql = "SELECT * FROM decks WHERE deck_id = :deck_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['deck_id' => $deckId]);
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deck) {
        die("Deck not found.");
    }

    $sql = "SELECT * FROM cards WHERE deck_id = :deck_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['deck_id' => $deckId]);
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching deck or cards: " . $e->getMessage());
}

// Hantera inlämning av nytt kort
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardName = $_POST['card_name'] ?? '';
    if (!empty($cardName)) {
        try {
            $sql = "INSERT INTO cards (card_name, deck_id) VALUES (:card_name, :deck_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['card_name' => $cardName, 'deck_id' => $deckId]);
            header("Location: cards.php?deck_id=$deckId");
            exit;
        } catch (PDOException $e) {
            die("Error adding card: " . $e->getMessage());
        }
    } else {
        echo "Card name cannot be empty!";
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

    <!-- Formulär för att lägga till kort -->
    <form method="POST">
        <label for="card_name">New Card Name:</label>
        <input type="text" id="card_name" name="card_name" required>
        <button type="submit">Add Card</button>
    </form>

    <!-- Lista över alla kort i däcket -->
    <h2>Cards in Deck</h2>
    <ul>
        <?php foreach ($cards as $card): ?>
            <li><?= htmlspecialchars($card['card_name']) ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="index.php">Back to Decks</a>
</body>
</html>