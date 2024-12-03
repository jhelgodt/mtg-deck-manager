<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MTG Deck Manager</title>
</head>
<body>
    <h1>Welcome to MTG Deck Manager</h1>
    <ul>
        <li><a href="./cards/read.php">View All Cards</a></li>
        <li><a href="./cards/create.php">Add New Card</a></li>
    </ul>
</body>
</html>