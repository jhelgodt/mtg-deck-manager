<?php
// Absolut sökväg till projektets rot
define('BASE_PATH', __DIR__ . '/../');

// Databasanslutning
$servername = "mariadb";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}