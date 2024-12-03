<?php
/**
 * Laddar miljövariabler från en .env-fil och sparar dem i $_ENV.
 *
 * @param string $filePath Sökväg till .env-filen.
 * @throws Exception Om .env-filen inte hittas.
 */
function loadEnv($filePath)
{
    echo "Loading .env from: $filePath<br>"; // Debug-utskrift för att visa sökvägen

    if (!file_exists($filePath)) {
        throw new Exception("The .env file does not exist at: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorera kommentarer och tomma rader
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }

        // Kontrollera om linjen är ett giltigt nyckel-värde-par
        if (!strpos($line, '=')) {
            throw new Exception("Invalid format in .env file: $line");
        }

        // Dela upp nyckel och värde
        [$key, $value] = explode('=', $line, 2);

        // Trimma mellanslag och citattecken
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        echo "Loaded key: $key, value: $value<br>"; // Debug-utskrift för att visa laddade variabler

        // Lägg till variabeln i $_ENV och putenv
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}