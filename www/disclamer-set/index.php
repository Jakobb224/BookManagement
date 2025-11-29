<?php
// Session MUSS ganz am Anfang stehen!
session_start();

// Datei mit dem gespeicherten Passwort
$file = "../passwords.txt";

// Prüfen, ob die Datei existiert und lesbar ist
if (file_exists($file) && is_readable($file)) {
    // Passwort aus der Datei lesen und in eine Variable speichern
    $hash_from_file = trim(file_get_contents($file));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Passwort aus dem Formular
    $string = $_POST["password"];
    $secretKey = "GeheimerSchlüssel123";
    $hashedString = hash_hmac("sha256", $string, $secretKey);

    // Passwort setzen
    $password_from_user = $hashedString;

    // Überprüfen des Passworts
    if ($password_from_user === $hash_from_file) {
        // Setze Session-Variable für erfolgreichen Login
        $_SESSION['logged_in'] = true;
        // Weiterleitung zur Seite mit der Medienverwaltung
        header("Location: settings.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMD_Library v1.3.3.7</title>
    <!-- Lade Stylesheet: Das 'Nerd-Modul' -->
    <link rel="stylesheet" href="./style.css">

    <!-- Lade Monospace-Fonts von Google: VT323 (Retro) und Share Tech Mono (Modern) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&family=VT323&display=swap" rel="stylesheet">

    <!-- Lade Ionen-Icon-Bibliothek für System-Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</head>

<body class="crt">
    <header>
        <nav>
            <nav>
                <!-- Titel mit animiertem Cursor -->
                <h1><span aria-hidden="true">//</span>Admin Login<span aria-hidden="true" class="cursor">_</span></h1>
            </nav>
            <!-- Link zu den Einstellungen, als Icon getarnt -->
            <a href="../index.php" class="settings-link" title="Systemeinstellungen">
                <ion-icon name="return-down-back-outline"></ion-icon>
            </a>
        </nav>
    </header>
    <div class="center">
        <div style="display: flex; flex-direction: column;">
            <div class="center">
                <table>
                    <tr>
                        <td>
                            <ion-icon class="person" name="person-outline"></ion-icon>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>ADMIN</p>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="controls">
                <form action="./index.php" method="post" class="search-form">
                    <input type="password" name="password" id="search-input" placeholder="> Masterkennwort eingeben...">
                    <button type="submit" class="btn">Weiter</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>