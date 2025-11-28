<?php
$string = "MeinGeheimerString";
$secretKey = "{fwaVY5sLaZrti!/*L^N@9WI";
$hashedString = hash_hmac("sha256", $string, $secretKey);

// Passwort setzen
$password = $hashedString;

// Datei, in die das Passwort gespeichert wird
$file = "passwords.txt";

// Passwort in die Datei schreiben
if (file_put_contents($file, $password . PHP_EOL, FILE_APPEND)) {
    echo "Passwort wurde gespeichert!";
} else {
    echo "Fehler beim Speichern des Passworts!";
}
?>

