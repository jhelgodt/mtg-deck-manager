<?php
// Inkludera connect.php med en absolut sökväg
// Inkludera connect.php med en absolut sökväg
require __DIR__ . '/../../db/connect.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['new_card_name'])) {
    $newCardName = htmlspecialchars($_POST['new_card_name']);

    try {
        $stmt = $conn->prepare("INSERT INTO cards (card_name) VALUES (:card_name)");
        $stmt->bindParam(':card_name', $newCardName);
        $stmt->execute();
        echo "<p>Card '$newCardName' has been added successfully.</p>";
    } catch (PDOException $e) {
        echo "<p>Error adding card: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<h2>Add a New Card</h2>
<form method="POST" action="cards/add.php">
    <label for="new_card_name">Card Name:</label>
    <input type="text" id="new_card_name" name="new_card_name" required>
    <button type="submit">Add Card</button>
</form>