<?php
require __DIR__ . '/../../db/connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['deck_name'])) {
    $deckName = htmlspecialchars($_POST['deck_name']);

    try {
        $stmt = $conn->prepare("INSERT INTO decks (deck_name) VALUES (:deck_name)");
        $stmt->bindParam(':deck_name', $deckName);
        $stmt->execute();
        echo "<p>Deck '$deckName' has been created successfully.</p>";
    } catch (PDOException $e) {
        echo "<p>Error creating deck: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<h2>Create a New Deck</h2>
<form method="POST" action="">
    <label for="deck_name">Deck Name:</label>
    <input type="text" id="deck_name" name="deck_name" required>
    <button type="submit">Create Deck</button>
</form>
<a href="../index.php">Back to Home</a>