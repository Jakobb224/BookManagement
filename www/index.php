<?php
// FILE: index.php
// INIT_SEQUENCE: Lade Konfiguration und validiere Umgebung
include "./config.php";

// Umgebungs-Check: Ist dies der erste Systemstart?
// Wenn 'first_visitor.txt' fehlt, leite zum Willkommensprotokoll um.
if (!file_exists('first_visitor.txt')) {
    header('Location: welcome.php');
    exit; // Terminiere Skriptausführung
}

// Definiere den Such-Query-Parameter. Default ist 'keiner'.
// 'q' (query) aus dem GET-Request abfangen.
$searchQuery = $_GET['q'] ?? '';

try {
    // Basis-SQL-Abfrage: Selektiere alle Felder von 'mediums', sortiert nach Titel (ASC).
    $sql = "SELECT * FROM mediums";

    // Dynamische Query-Modifikation: Wenn ein Such-Query vorhanden ist, füge WHERE-Klausel hinzu.
    if (!empty($searchQuery)) {
        // Parameterisierte Suche über 'Autor', 'Standort' oder 'Title'.
        $sql .= " WHERE Autor LIKE :search OR Standort LIKE :search OR Title LIKE :search";
    }

    // Sortierung anfügen
    $sql .= " ORDER BY Title ASC";

    // SQL-Statement vorbereiten
    $stmt = $pdo->prepare($sql);

    // Parameter binden, falls Suche aktiv ist
    if (!empty($searchQuery)) {
        // Binde den Such-String mit Wildcards an den ':search'-Parameter.
        $stmt->execute(['search' => "%" . $searchQuery . "%"]);
    } else {
        // Führe die Basis-Abfrage ohne Parameter aus.
        $stmt->execute();
    }

    // Alle Ergebnisse als assoziatives Array abrufen.
    $mediums = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // FATAL_ERROR: Datenbankoperation fehlgeschlagen. Zeige Fehlermeldung an.
    die("SYSTEM_ERROR: Datenabruf fehlgeschlagen. Meldung: " . $e->getMessage());
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
<!-- Aktiviere CRT-Monitor-Effekt -->
<body class="crt">

    <!-- Header-Sektion: Titel und Systemeinstellungen -->
    <header>
        <nav>
            <!-- Titel mit animiertem Cursor -->
            <h1><span aria-hidden="true">//</span>Buchverwaltung<span aria-hidden="true" class="cursor">_</span></h1>
        </nav>
        <!-- Link zu den Einstellungen, als Icon getarnt -->
        <a href="./disclamer-set" class="settings-link" title="Systemeinstellungen">
            <ion-icon name="bug-outline"></ion-icon>
        </a>
    </header>

    <main>
        <!-- Kontrollsektion: Suche und Hinzufügen -->
        <div class="controls">
            <!-- Such-Formular: Sendet als GET-Request an dieselbe Seite -->
            <form action="index.php" method="GET" class="search-form">
                <label for="search-input" class="sr-only">Suchen:</label>
                <input 
                    type="text" 
                    name="q" 
                    id="search-input"
                    placeholder="> Suchbegriff eingeben..." 
                    value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" class="btn">Suche</button>
            </form>
            <!-- Link zur 'Hinzufügen'-Seite, als Button gestylt -->
            <a href="./disclamer" class="btn btn-add">Liste bearbeiten</a>
        </div>

        <?php if (!empty($meldung)): ?>
            <!-- Systemmeldung (z.B. nach einer Aktion) -->
            <p class="system-message"><strong><?php echo htmlspecialchars($meldung); ?></strong></p>
        <?php endif; ?>


        <?php if (empty($mediums) && !empty($searchQuery)): ?>
            <!-- Warnung: Nichts gefunden -->
            <p id="Db-nothing" class="warning">WARNUNG: Kein Eintrag entspricht dem Query [<?php echo htmlspecialchars($searchQuery); ?>]</p>
        <?php elseif (empty($mediums)): ?>
             <!-- Warnung: Datenbank ist leer -->
            <p id="Db-nothing" class="warning">ALARM: Datenbank ist leer!!! Kein Medium initialisiert.</p>
        <?php else: ?>
            <!-- Datentabelle im Matrix-Stil -->
            <table class="matrix-table">
                <thead>
                    <tr>
                        <td class="th">Titel</td>
                        <td class="th">Autor</td>
                        <td class="th">Standort</td>
                    </tr>
                </thead>
                <tbody>
                    <!-- Iteriere durch alle gefundenen Einträge -->
                    <?php foreach ($mediums as $medium): ?>
                        <tr>
                            <!-- Daten ausgeben, HTML-Sonderzeichen escapen -->
                            <td data-label="Titel"><?php echo htmlspecialchars($medium['Title']); ?></td>
                            <td data-label="Autor"><?php echo htmlspecialchars($medium['Autor']); ?></td>
                            <td data-label="Standort"><?php echo htmlspecialchars($medium['Standort']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>