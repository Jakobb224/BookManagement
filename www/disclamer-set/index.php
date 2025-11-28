<?php
// Datei mit dem gespeicherten Passwort
$file = "../passwords.txt";

// Prüfen, ob die Datei existiert und lesbar ist
if (file_exists($file) && is_readable($file)) {
    // Passwort aus der Datei lesen und in eine Variable speichern
    $hash_from_file = trim(file_get_contents($file));
}
?>

<?php
session_start();


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
        header("Location: setting.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disclamer</title>
    <link rel="stylesheet" href="../style.css">
    <script>
        function back() {
            location.replace("../index.php")
        }
    </script>
</head>

<body>
    <nav>
        <button onclick="back()" style="margin-right: 10px;">Zurrück</button>
        <h1>VORSICHT</h1>
    </nav>
    <div style="margin-bottom: 75px; display: flex; justify-content: center;">
        <div style="display: flex; flex-direction: column;">
            <h4 style="color: red;">Der folgende Teil dieser Software ist nur speziel für den Admin gedacht.</h4>
            <h4 style="color: green;">Wir bitten Sie deshalb sich mit Ihrem Masterpasswort anzumelden</h4>
        </div>
    </div>
    <div class="center">
        <div style="display: flex; flex-direction: column;">
            <div class="center">
                <img style="margin-bottom: 10px;" src="./admin.png" width="200px" height="200px">
            </div>
            <form action="./index.php" method="post">
                <input name="password" type="password" placeholder="Passwort">
                <button type="submit">Weiter</button>
            </form>
        </div>
    </div>
</body>

</html>