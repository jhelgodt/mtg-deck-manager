<?php
require '../db/connect.php'; // Inkludera databasanslutningen

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['card_name'])) {
    $cardName = urlencode($_POST['card_name']); // Kodar kortnamnet för URL
    $url = "https://api.scryfall.com/cards/named?exact=$cardName";

    // Skapa cURL-förfrågan
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Lägg till headers för att uppfylla Scryfalls krav
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: MagicDeckBuilder/1.0 (N/A)',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);

    // Kontrollera om det uppstod ett cURL-fel
    if (curl_errno($ch)) {
        echo "<p>cURL error: " . curl_error($ch) . "</p>";
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // Konvertera JSON-svaret till en PHP-array
    $data = json_decode($response, true);

    // Kontrollera om API:et returnerade ett fel
    if (isset($data['object']) && $data['object'] === 'error') {
        echo "<p>Error from Scryfall: " . htmlspecialchars($data['details']) . "</p>";
    } elseif (!empty($data)) {
        // Uppdatera databasen med hämtad data
        try {
            $stmt = $conn->prepare("
                UPDATE cards
                SET mana_cost = :mana_cost,
                    type_line = :type_line,
                    set_name = :set_name,
                    rarity = :rarity
                WHERE card_name = :card_name
            ");
            $stmt->bindParam(':mana_cost', $data['mana_cost']);
            $stmt->bindParam(':type_line', $data['type_line']);
            $stmt->bindParam(':set_name', $data['set_name']);
            $stmt->bindParam(':rarity', $data['rarity']);
            $stmt->bindParam(':card_name', $_POST['card_name']);
            $stmt->execute();

            // Om uppdateringen lyckas, skicka tillbaka användaren till index.php
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            echo "<p>Error updating card: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>Card not found on Scryfall.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>