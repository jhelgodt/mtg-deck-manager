<?php
// Databasanslutningsdetaljer
$servername = "mariadb";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

try {
    // Skapa en PDO-anslutning
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Om anslutningen misslyckas, avsluta och skriv ut felet
    die("Connection failed: " . $e->getMessage());
}
?>