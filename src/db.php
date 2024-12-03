<?php

require_once BASE_PATH . '/config.php';
try {
    // Databasinställningar
    $servername = 'mariadb';  // MariaDB-tjänstens namn i Docker Compose
    $username = 'mariadb';    // Användarnamn
    $password = 'mariadb';    // Lösenord
    $dbname = 'mariadb';      // Databasnamn
    $charset = 'utf8mb4';     // Teckenuppsättning

    // PDO Data Source Name (DSN)
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

    // Skapa en ny PDO-anslutning
    $conn = new PDO($dsn, $username, $password);

    // Ställ in PDO-felhanteringsläge
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kontrollera anslutningen (valfritt)
    echo "Connected successfully";

} catch (PDOException $e) {
    // Logga felet istället för att visa det i produktion
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}