<?php

$servername = "mariadb"; // Fixat variabelnamnet
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fixat stavfel
    echo "Connected successfully";
} catch (PDOException $e) { // Fixat stavfel
    echo "Connection failed: " . $e->getMessage();
}

?>