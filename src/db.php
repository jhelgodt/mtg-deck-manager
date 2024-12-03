<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php

try {
    // Ladda miljövariabler från .env
    loadEnv(__DIR__ . '/../.env');

    // Anslut till databasen
    $servername = getenv('MYSQL_HOST') ?: 'localhost';
    $username = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: '';
    $dbname = getenv('MYSQL_DATABASE') ?: 'test';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Databasen är ansluten, ingen direkt utskrift krävs
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}