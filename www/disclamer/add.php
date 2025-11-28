<?php
session_start();

// Überprüfen, ob der Benutzer eingeloggt ist
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Wenn nicht eingeloggt, zur Login-Seite umleiten
    header("Location: ../index.php");
    exit();
}

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
    $sql = "SELECT * FROM mediums ORDER BY Title ASC";

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
    <link rel="stylesheet" href="./style1.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editieren</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <script>
        function start() {
            window.location.replace("./logout.php")
        }
    </script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>

<body>
    <nav>
        <button onclick="start()" style="margin-right: 20px;">
            <ion-icon name="home-outline"></ion-icon>
        </button>
        <h1>Buchverwaltung</h1>
    </nav>

    <?php if (!empty($meldung)): ?>
        <p><strong><?php echo htmlspecialchars($meldung); ?></strong></p>
    <?php endif; ?>

    <!-- Formular zum Hinzufügen -->
    <div class="center">
        <form id="add" method="POST">
            <input placeholder="Autor:" type="text" id="autor" name="autor" required>

            <input placeholder="Standort:" type="text" id="standort" name="standort" required>

            <input placeholder="Titel:" type="text" id="title" name="title" required>

            <button type="submit" name="add">Medium hinzufügen</button>
        </form>
    </div>
    <!-- Prüfen, ob die Tabelle leer ist -->
    <?php if (empty($mediums)): ?>
        <p style="margin-top: 50px;" id="Db-nothing"><strong>Die Tabelle ist leer!!!</strong></p>
    <?php else: ?>
        <!-- Tabelle mit allen Medien -->
        <table>
            <thead>
                <tr>
                    <td class="th">Titel:</td>
                    <td class="th">Autor:</td>
                    <td class="th">Standort:</td>
                    <td class="th">Aktionen:</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mediums as $medium): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($medium['Title']); ?></td>
                        <td><?php echo htmlspecialchars($medium['Autor']); ?></td>
                        <td><?php echo htmlspecialchars($medium['Standort']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="delete" value="<?php echo htmlspecialchars($medium['Title']); ?>">
                                <button type="submit"
                                    onclick="return confirm('Möchtest du dieses Medium wirklich löschen?');">Löschen</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
