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

    if (isset($data['object']) && $data['object'] === 'error') {
        echo "<p>Error from Scryfall: " . htmlspecialchars($data['details']) . "</p>";
    } elseif (!empty($data)) {
        // Förbered data för bindParam
        $color_identity = isset($data['color_identity']) ? implode(',', $data['color_identity']) : null;
        $produced_mana = isset($data['produced_mana']) ? implode(',', $data['produced_mana']) : null;
        $games = isset($data['games']) ? implode(',', $data['games']) : null;

        // Uppdatera databasen med all hämtad data
        try {
            $stmt = $conn->prepare("
                UPDATE cards
                SET 
                    mana_cost = :mana_cost,
                    type_line = :type_line,
                    set_name = :set_name,
                    rarity = :rarity,
                    artist = :artist,
                    color_identity = :color_identity,
                    produced_mana = :produced_mana,
                    cmc = :cmc,
                    games = :games,
                    reserved = :reserved,
                    foil = :foil,
                    nonfoil = :nonfoil,
                    oversized = :oversized,
                    promo = :promo,
                    reprint = :reprint,
                    usd_price = :usd_price,
                    eur_price = :eur_price,
                    tix_price = :tix_price,
                    toughness = :toughness,
                    power = :power,
                    image_uri = :image_uri,
                    last_updated = CURRENT_TIMESTAMP
                WHERE card_name = :card_name
            ");
            $stmt->bindParam(':mana_cost', $data['mana_cost']);
            $stmt->bindParam(':type_line', $data['type_line']);
            $stmt->bindParam(':set_name', $data['set_name']);
            $stmt->bindParam(':rarity', $data['rarity']);
            $stmt->bindParam(':artist', $data['artist']);
            $stmt->bindParam(':color_identity', $color_identity);
            $stmt->bindParam(':produced_mana', $produced_mana);
            $stmt->bindParam(':cmc', $data['cmc']);
            $stmt->bindParam(':games', $games);
            $stmt->bindParam(':reserved', $data['reserved'], PDO::PARAM_BOOL);
            $stmt->bindParam(':foil', $data['foil'], PDO::PARAM_BOOL);
            $stmt->bindParam(':nonfoil', $data['nonfoil'], PDO::PARAM_BOOL);
            $stmt->bindParam(':oversized', $data['oversized'], PDO::PARAM_BOOL);
            $stmt->bindParam(':promo', $data['promo'], PDO::PARAM_BOOL);
            $stmt->bindParam(':reprint', $data['reprint'], PDO::PARAM_BOOL);
            $stmt->bindParam(':usd_price', $data['prices']['usd']);
            $stmt->bindParam(':eur_price', $data['prices']['eur']);
            $stmt->bindParam(':tix_price', $data['prices']['tix']);
            $stmt->bindParam(':toughness', $data['toughness']);
            $stmt->bindParam(':power', $data['power']);
            $stmt->bindParam(':image_uri', $data['image_uris']['normal']);
            $stmt->bindParam(':card_name', $_POST['card_name']);
            $stmt->execute();

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