<?php
include 'templates/header.php'; // Header
require '../db/connect.php';    // Databasanslutning

// Visa listan över kort
include 'cards/list.php';

include 'templates/footer.php'; // Footer
?>