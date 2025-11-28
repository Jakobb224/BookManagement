<?php
include 'config.php'; // Datenbankverbindung

$meldung = null;
$searchQuery = ""; // Initialisiere die Suchabfrage

// Medium hinzufügen (Formularverarbeitung)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $autor = trim($_POST['autor']);
    $standort = trim($_POST['standort']);
    $title = trim($_POST['title']);

    if (!empty($autor) && !empty($standort) && !empty($title)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO mediums (Autor, Standort, Title) VALUES (:autor, :standort, :title)");
            $stmt->execute(['autor' => $autor, 'standort' => $standort, 'title' => $title]);
            $meldung = "Medium erfolgreich hinzugefügt!";
        } catch (PDOException $e) {
            $meldung = "Fehler: " . $e->getMessage();
        }
    } else {
        $meldung = "Bitte alle Felder ausfüllen!";
    }
}

// Medium löschen (per POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $titleToDelete = $_POST['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM mediums WHERE Title = :title");
        $stmt->execute(['title' => $titleToDelete]);
        $meldung = "Medium erfolgreich gelöscht!";
    } catch (PDOException $e) {
        $meldung = "Fehler beim Löschen: " . $e->getMessage();
    }
}

// Wenn eine Suche durchgeführt wird
if (isset($_POST['search'])) {
    $searchQuery = trim($_POST['search']);
}

// SQL-Abfrage mit optionaler Suche
try {
    $sql = "SELECT * FROM backup";

    if (!empty($searchQuery)) {
        // Wenn ein Suchbegriff vorhanden ist, führe die Suche auf allen Spalten durch
        $sql .= " WHERE Autor LIKE :search OR Standort LIKE :search OR Title LIKE :search";
    }

    $stmt = $pdo->prepare($sql);

    // Wenn ein Suchbegriff vorhanden ist, binde ihn an die Abfrage
    if (!empty($searchQuery)) {
        $stmt->execute(['search' => "%" . $searchQuery . "%"]);
    } else {
        $stmt->execute();
    }

    $mediums = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fehler beim Abrufen der Daten: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <link rel="stylesheet" href="./style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suche(Backup)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <script>
        function start() {
            window.location.replace("./index.php")
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

    <?php if (!empty($meldung)): ?>
        <p><strong><?php echo htmlspecialchars($meldung); ?></strong></p>
    <?php endif; ?>

    <div class="center">
        <!-- Suchformular -->
        <form method="POST">
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>"
                placeholder="Suche nach Autor, Standort oder Titel">
            <button class="button" type="submit">🔎</button>
        </form>
    </div>

    <!-- Prüfen, ob die Tabelle leer ist -->
    <?php if (empty($mediums)): ?>
        <p style="margin-top: 50px;" id="Db-nothing"><strong>Die Tabelle ist leer oder es wurden keine Ergebnisse für die
                Suche gefunden!!!</strong></p>
    <?php else: ?>
        <!-- Tabelle mit allen Medien -->
        <table>
            <thead>
                <tr>
                    <td class="th">Titel:</td>
                    <td class="th">Autor:</td>
                    <td class="th">Standort:</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mediums as $medium): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($medium['Title']); ?></td>
                        <td><?php echo htmlspecialchars($medium['Autor']); ?></td>
                        <td><?php echo htmlspecialchars($medium['Standort']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>