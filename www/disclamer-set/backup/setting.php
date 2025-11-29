<?php
include 'config.php'; // Verbindung zur Datenbank

// Variablen für Quell- und Zieltabelle
$quelleTabelle = 'backup';
$zielTabelle = 'mediums';
$datenbank = 'myapp'; // ← WICHTIG: Deine Datenbank heißt 'myapp', nicht 'Bücher'!

// Funktion zum Überschreiben der Tabelle
function tabelleUeberschreiben($pdo, $quelleTabelle, $zielTabelle, $datenbank)
{
    try {
        // Verbindung zur Datenbank wählen
        $pdo->exec("USE `$datenbank`");
        
        // Schritt 1: Zieltabelle löschen
        $pdo->exec("DROP TABLE IF EXISTS `$zielTabelle`");
        
        // Schritt 2: Tabellenstruktur kopieren
        $pdo->exec("CREATE TABLE `$zielTabelle` LIKE `$quelleTabelle`");
        
        // Schritt 3: Daten kopieren
        $pdo->exec("INSERT INTO `$zielTabelle` SELECT * FROM `$quelleTabelle`");
        
        echo '
        <div style="padding: 10px; color: rgb(211, 211, 211); position: fixed; right: 25px; top: 100px; background-color: rgb(82, 77, 77); height: 50px; border-radius: 3px; width: 200px;">
            Tabelle erfolgreich wiederhergestellt!
            <button onclick="not()" style="border: none;position: fixed; right: 25px; top: 130px; background-color: rgba(0, 0, 0, 0); color: red;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>
        ';
    } catch (PDOException $e) {
        echo "Fehler: " . $e->getMessage(); // ← WICHTIG: . statt + für String-Verkettung!
    }
}

// Wenn der Button gedrückt wurde
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    tabelleUeberschreiben($pdo, $quelleTabelle, $zielTabelle, $datenbank);
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einstellungen(Backup)</title>
    <link rel="stylesheet" href="./style.css">
    <script>
        function start() {
            window.location.replace("./index.php")
        }
        function not() {
            window.location.replace("./setting.php");
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <nav>
        <button class="button" onclick="start()" style="margin-right: 20px;">
            <ion-icon name="home-outline"></ion-icon>
        </button>
        <h1>Buchverwaltung(Backup)</h1>
    </nav>
    <p class="backup">
        Backups:
        <hr>
    </p>
    <form method="post">
        <button class="button" type="submit">
            Tabelle wiederherstellen
        </button>
    </form>

</body>
</html>