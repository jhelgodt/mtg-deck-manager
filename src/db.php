<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php

try {
    // Ladda miljövariabler från .env
    loadEnv(__DIR__ . '/../.env');

    // Debug: Visa laddade variabler
    echo "MYSQL_HOST: " . getenv('MYSQL_HOST') . "<br>";
    echo "MYSQL_USER: " . getenv('MYSQL_USER') . "<br>";
    echo "MYSQL_PASSWORD: " . getenv('MYSQL_PASSWORD') . "<br>";
    echo "MYSQL_DATABASE: " . getenv('MYSQL_DATABASE') . "<br>";

    // Anslut till databasen
    $servername = getenv('MYSQL_HOST') ?: 'localhost';
    $username = getenv('MYSQL_USER') ?: 'root';
    $password = getenv('MYSQL_PASSWORD') ?: '';
    $dbname = getenv('MYSQL_DATABASE') ?: 'test';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully<br>";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
    die("Database connection failed. Please try again later.");
}