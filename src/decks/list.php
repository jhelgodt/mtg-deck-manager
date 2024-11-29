<?php
require __DIR__ . '/../../db/connect.php'; 

// Hämta alla decks från databasen
try {
    $stmt = $conn->query("SELECT * FROM decks");
    $decks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<p>Error fetching decks. Please try again later.</p>";
    $decks = []; // Sätt en tom lista om ett fel uppstår
}
?>

<h2>Deck Management</h2>
<nav>
    <a href="../index.php">Back to Home</a>
</nav>
<ul>
    <?php if (!empty($decks)): ?>
        <?php foreach ($decks as $deck): ?>
            <li>
                <?= htmlspecialchars($deck['name']) ?>
                <!-- Korrekt länk till decks/view.php -->
                <a href="decks/view.php?deck_id=<?= htmlspecialchars($deck['id']) ?>">View</a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No decks found. <a href="decks/create.php">Create a New Deck</a></li>
    <?php endif; ?>
</ul>