<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['card_name'])) {
    $cardName = urlencode($_POST['card_name']); // Kodar kortnamnet för URL
    $url = "https://api.scryfall.com/cards/named?exact=$cardName";

    // Gör ett anrop till Scryfall API
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    if (!empty($data)) {
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