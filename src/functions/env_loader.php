<?php
/**
 * Laddar miljövariabler från en .env-fil och sparar dem i $_ENV.
 *
 * @param string $filePath Sökväg till .env-filen.
 * @throws Exception Om .env-filen inte hittas.
 */
function loadEnv($filePath)
{
    // Kommentera eller ta bort debug-utskrifter
    // echo "Loading .env from: $filePath<br>";

    if (!file_exists($filePath)) {
        throw new Exception("The .env file does not exist at: $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || trim($line) === '') {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}