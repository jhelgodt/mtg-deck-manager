<?php
include 'templates/header.php'; // Header
require __DIR__ . '/../db/connect.php';

// Visa listan över kort
include 'cards/list.php';

include 'templates/footer.php'; // Footer
?>