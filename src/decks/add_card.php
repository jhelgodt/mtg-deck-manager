<?php
require __DIR__ . '/../../db/connect.php';

// Kontrollera om formuläret är skickat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardId = intval($_POST['card_id']);
    $deckId = intval($_POST['deck_id']);
    $quantity = intval($_POST['quantity']);
    $status = htmlspecialchars($_POST['status']);

    try {
        if ($status === 'Active') {
            // Lägg till kort i quantity_active
            $stmt = $conn->prepare("
                INSERT INTO deck_cards (deck_id, card_id, quantity_active, quantity_considering)
                VALUES (:deck_id, :card_id, :quantity, 0)
                ON DUPLICATE KEY UPDATE
                    quantity_active = quantity_active + :quantity
            ");
        } elseif ($status === 'Considering') {
            // Lägg till kort i quantity_considering
            $stmt = $conn->prepare("
                INSERT INTO deck_cards (deck_id, card_id, quantity_active, quantity_considering)
                VALUES (:deck_id, :card_id, 0, :quantity)
                ON DUPLICATE KEY UPDATE
                    quantity_considering = quantity_considering + :quantity
            ");
        } else {
            throw new Exception("Invalid status provided.");
        }

        // Bind parametrar och exekvera
        $stmt->bindParam(':deck_id', $deckId, PDO::PARAM_INT);
        $stmt->bindParam(':card_id', $cardId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->execute();

        // Omdirigera till kortlistan efter framgång
        header("Location: ../cards/list.php");
        exit;
    } catch (PDOException $e) {
        echo "<p>Error adding card to deck: " . htmlspecialchars($e->getMessage()) . "</p>";
    } catch (Exception $e) {
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}