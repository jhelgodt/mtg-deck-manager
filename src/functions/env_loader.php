<?php
/**
 * Laddar miljövariabler från en .env-fil och sparar dem i $_ENV.
 *
 * @param string $filePath Sökväg till .env-filen.
 * @throws Exception Om .env-filen inte hittas.
 */
function loadEnv($filePath)
{
    if (!file_exists($filePath)) {
        throw new Exception("The .env file does not exist at: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorera kommentarer och tomma rader
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }

        // Dela upp nyckel och värde vid '='
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}