<?php
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
        // Visa den hämtade datan
        echo "<h1>Card Details for: " . htmlspecialchars($data['name']) . "</h1>";
        echo "<p><strong>Mana Cost:</strong> " . htmlspecialchars($data['mana_cost'] ?? 'N/A') . "</p>";
        echo "<p><strong>Type Line:</strong> " . htmlspecialchars($data['type_line'] ?? 'N/A') . "</p>";
        echo "<p><strong>Set Name:</strong> " . htmlspecialchars($data['set_name'] ?? 'N/A') . "</p>";
        echo "<p><strong>Rarity:</strong> " . htmlspecialchars($data['rarity'] ?? 'N/A') . "</p>";
    } else {
        echo "<p>Card not found on Scryfall.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}
?>