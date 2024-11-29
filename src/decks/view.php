<?php
require __DIR__ . '/../../db/connect.php';

// Kontrollera om deck_id är skickat via GET
if (!isset($_GET['deck_id']) || empty($_GET['deck_id'])) {
    echo "<p>No deck ID provided.</p>";
    exit;
}

$deckId = intval($_GET['deck_id']); // Omvandla deck_id till ett heltal

try {
    // Hämta deck-information från databasen
    $stmt = $conn->prepare("SELECT * FROM decks WHERE id = :id");
    $stmt->bindParam(':id', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deck) {
        echo "<p>Deck not found.</p>";
        exit;
    }
} catch (PDOException $e) {
    echo "<p>Error fetching deck details: " . htmlspecialchars($e->getMessage()) . "</p>";
    exit;
}

// Visa deck-informationen
?>
<h2>Deck: <?= htmlspecialchars($deck['name']) ?></h2>
<p>Created at: <?= htmlspecialchars($deck['created_at']) ?></p>