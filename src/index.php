<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen

// Hämta alla däck
try {
    $sql = "SELECT * FROM decks ORDER BY created_at DESC";
    $stmt = $conn->query($sql);
    $decks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching decks: " . $e->getMessage());
}

// Hantera formulärinlämning för att skapa ett nytt däck
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deckName = $_POST['deck_name'] ?? '';
    if (!empty($deckName)) {
        try {
            $sql = "INSERT INTO decks (deck_name) VALUES (:deck_name)";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['deck_name' => $deckName]);
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            die("Error creating deck: " . $e->getMessage());
        }
    } else {
        echo "Deck name cannot be empty!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck Manager</title>
</head>
<body>
    <h1>Deck Manager</h1>

    <!-- Formulär för att skapa nya däck -->
    <form method="POST">
        <label for="deck_name">New Deck Name:</label>
        <input type="text" id="deck_name" name="deck_name" required>
        <button type="submit">Create Deck</button>
    </form>

    <!-- Länk till alla kort -->
    <h2>Manage Cards</h2>
    <a href="all_cards.php">View All Cards</a>

    <!-- Lista över alla däck -->
    <h2>All Decks</h2>
    <ul>
        <?php foreach ($decks as $deck): ?>
            <li>
                <a href="deck.php?deck_id=<?= $deck['deck_id'] ?>">
                    <?= htmlspecialchars($deck['deck_name']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>