<?php
// Inkludera sidhuvud
include_once 'templates/header.php';

// Anslut till databasen
require_once __DIR__ . '/../db/connect.php';

// Visa alla kort
include_once 'cards/list.php';

// Visa alla decks
include_once 'decks/list.php';

// Inkludera sidfot
include_once 'templates/footer.php';
?>