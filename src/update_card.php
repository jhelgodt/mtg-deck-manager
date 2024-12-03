<?php
require_once __DIR__ . '/../config.php'; // Inkludera config.php
require_once DB_PATH; // Inkludera db.php via den definierade sökvägen

function fetchCardData($cardName) {
    $apiUrl = 'https://api.scryfall.com/cards/named?exact=' . urlencode($cardName);

    // Skicka API-förfrågan
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: MTGDeckManager/1.0',
                'Accept: application/json',
            ],
        ],
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($apiUrl, false, $context);

    if ($response === false) {
        throw new Exception('Failed to fetch card data from Scryfall.');
    }

    $card = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON response from Scryfall.');
    }

    return $card;
}

function updateCardInDatabase($conn, $card) {
    $stmt = $conn->prepare("
        INSERT INTO cards (card_name, mana_cost, type_line, keywords, set_name, rarity, oracle_text, artist, 
            color_identity, produced_mana, cmc, reprint, usd_price, eur_price, tix_price, toughness, power, image_uri)
        VALUES (:card_name, :mana_cost, :type_line, :keywords, :set_name, :rarity, :oracle_text, :artist, 
            :color_identity, :produced_mana, :cmc, :reprint, :usd_price, :eur_price, :tix_price, :toughness, :power, :image_uri)
        ON DUPLICATE KEY UPDATE 
            mana_cost = VALUES(mana_cost), type_line = VALUES(type_line), keywords = VALUES(keywords), 
            set_name = VALUES(set_name), rarity = VALUES(rarity), oracle_text = VALUES(oracle_text), 
            artist = VALUES(artist), color_identity = VALUES(color_identity), produced_mana = VALUES(produced_mana), 
            cmc = VALUES(cmc), reprint = VALUES(reprint), usd_price = VALUES(usd_price), eur_price = VALUES(eur_price), 
            tix_price = VALUES(tix_price), toughness = VALUES(toughness), power = VALUES(power), image_uri = VALUES(image_uri)
    ");

    $stmt->execute([
        ':card_name' => $card['name'] ?? '',
        ':mana_cost' => $card['mana_cost'] ?? '',
        ':type_line' => $card['type_line'] ?? '',
        ':keywords' => implode(',', $card['keywords'] ?? []),
        ':set_name' => $card['set_name'] ?? '',
        ':rarity' => $card['rarity'] ?? '',
        ':oracle_text' => $card['oracle_text'] ?? '',
        ':artist' => $card['artist'] ?? '',
        ':color_identity' => implode(',', $card['color_identity'] ?? []),
        ':produced_mana' => implode(',', $card['produced_mana'] ?? []),
        ':cmc' => $card['cmc'] ?? 0,
        ':reprint' => $card['reprint'] ?? 0,
        ':usd_price' => $card['prices']['usd'] ?? null,
        ':eur_price' => $card['prices']['eur'] ?? null,
        ':tix_price' => $card['prices']['tix'] ?? null,
        ':toughness' => $card['toughness'] ?? '',
        ':power' => $card['power'] ?? '',
        ':image_uri' => $card['image_uris']['normal'] ?? ''
    ]);
}

// Aktivera utmatningsbuffring
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardName = $_POST['card_name'] ?? null;

    if (!$cardName) {
        die("Card name is required.");
    }

    try {
        // Hämta data för kortet
        $card = fetchCardData($cardName);

        // Uppdatera kortet i databasen
        updateCardInDatabase($conn, $card);

        // Omdirigera vid framgång
        header("Location: all_cards.php?success=1");
        exit;
    } catch (Exception $e) {
        error_log("Error updating card: " . $e->getMessage());
        header("Location: all_cards.php?error=1");
        exit;
    }
}

ob_end_flush();