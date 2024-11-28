<?php

$servername = "mariadb";
$username = "mariadb";
$password = "mariadb";
$dbname = "mariadb";

try {
    // Skapa en PDO-anslutning
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully<br>";

    // Skapa en tabell om den inte redan finns
    $conn->exec("
        CREATE TABLE IF NOT EXISTS test_table (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Table 'test_table' created or already exists.<br>";

    // Infoga ett exempelvärde
    $conn->exec("INSERT INTO test_table (name) VALUES ('Hello, World!')");
    echo "Inserted a row into 'test_table'.<br>";

    // Hämta och visa värden från tabellen
    $stmt = $conn->query("SELECT * FROM test_table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Data in 'test_table':<br>";
    foreach ($rows as $row) {
        echo "ID: " . $row['id'] . " - Name: " . $row['name'] . " - Created At: " . $row['created_at'] . "<br>";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>