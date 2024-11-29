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

<h2>Your Decks</h2>
<nav>
    <a href="../index.php">Home</a> |
    <a href="../cards/list.php">View Cards</a>
</nav>
<ul>
    <?php if (!empty($decks)): ?>
        <?php foreach ($decks as $deck): ?>
            <li>
                <?= htmlspecialchars($deck['name']) ?>
                <!-- Länk för att visa deckets innehåll -->
                <a href="view.php?deck_id=<?= htmlspecialchars($deck['id']) ?>">View</a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li>No decks found.</li>
    <?php endif; ?>
</ul>
<a href="create.php">Create a New Deck</a>