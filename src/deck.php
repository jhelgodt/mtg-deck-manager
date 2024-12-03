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

// Hantera inlämning för att lägga till, uppdatera eller ta bort kort
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;
    $cardId = $_POST['card_id'] ?? null;

    if ($action === 'add_card' && $cardId) {
        $quantityActive = $_POST['quantity_active'] ?? 0;
        $quantityConsidering = $_POST['quantity_considering'] ?? 0;

        try {
            $sql = "INSERT INTO deck_cards (deck_id, card_id, quantity_active, quantity_considering)
                    VALUES (:deck_id, :card_id, :quantity_active, :quantity_considering)
                    ON DUPLICATE KEY UPDATE 
                    quantity_active = :quantity_active,
                    quantity_considering = :quantity_considering";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'deck_id' => $deckId,
                'card_id' => $cardId,
                'quantity_active' => $quantityActive,
                'quantity_considering' => $quantityConsidering
            ]);
        } catch (PDOException $e) {
            die("Error adding card to deck: " . $e->getMessage());
        }
    } elseif ($action === 'update_card' && $cardId) {
        $quantityActive = $_POST['quantity_active'] ?? 0;
        $quantityConsidering = $_POST['quantity_considering'] ?? 0;

        try {
            $sql = "UPDATE deck_cards 
                    SET quantity_active = :quantity_active, quantity_considering = :quantity_considering
                    WHERE deck_id = :deck_id AND card_id = :card_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'deck_id' => $deckId,
                'card_id' => $cardId,
                'quantity_active' => $quantityActive,
                'quantity_considering' => $quantityConsidering
            ]);
        } catch (PDOException $e) {
            die("Error updating card in deck: " . $e->getMessage());
        }
    } elseif ($action === 'remove_card' && $cardId) {
        try {
            $sql = "DELETE FROM deck_cards WHERE deck_id = :deck_id AND card_id = :card_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'deck_id' => $deckId,
                'card_id' => $cardId
            ]);
        } catch (PDOException $e) {
            die("Error removing card from deck: " . $e->getMessage());
        }
    }

    header("Location: deck.php?deck_id=$deckId");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Deck: <?= htmlspecialchars($deck['deck_name']) ?></title>
</head>
<body>
    <h1>Deck: <?= htmlspecialchars($deck['deck_name']) ?></h1>

    <!-- Visa kort i deck -->
    <h2>Cards in Deck</h2>
    <table>
        <thead>
            <tr>
                <th>Card Name</th>
                <th>Mana Cost</th>
                <th>Image</th>
                <th>Active</th>
                <th>Considering</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cards as $card): ?>
                <tr>
                    <td><?= htmlspecialchars($card['card_name']) ?></td>
                    <td><?= htmlspecialchars($card['mana_cost'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($card['image_uri'])): ?>
                            <img class="card-image" src="<?= htmlspecialchars($card['image_uri']) ?>" alt="Card Image">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="action" value="update_card">
                            <input type="hidden" name="card_id" value="<?= $card['card_id'] ?>">
                            <input type="number" name="quantity_active" value="<?= $card['quantity_active'] ?>" min="0">
                    </td>
                    <td>
                            <input type="number" name="quantity_considering" value="<?= $card['quantity_considering'] ?>" min="0">
                    </td>
                    <td>
                            <button type="submit">Update</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="remove_card">
                            <input type="hidden" name="card_id" value="<?= $card['card_id'] ?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to remove this card?');">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Formulär för att lägga till kort -->
    <h2>Add Card to Deck</h2>
    <form method="POST">
        <input type="hidden" name="action" value="add_card">
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