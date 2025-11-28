
<?php
if (isset($_POST["master"])) {
    $string = $_POST["master"];
    $secretKey = "GeheimerSchlüssel123";
    $hashedString = hash_hmac("sha256", $string, $secretKey);

    // Passwort setzen
    $password = $hashedString;

    // Datei, in die das Passwort gespeichert wird
    $file = "./passwords.txt";

    // Passwort in die Datei schreiben
    file_put_contents($file, $password . PHP_EOL, FILE_APPEND);
}
?>

<?php
// Datei, die überprüft, ob der Welcome-Screen schon angezeigt wurde
$file = 'first_visitor.txt';

// Prüfen, ob die Datei existiert
if (file_exists($file)) {
    // Datei erstellen, um zu speichern, dass der Welcome-Screen gezeigt wurde
    header('Location: index.php');
    exit;
    // Willkommensnachricht anzeigen
}
else {
    file_put_contents($file, "shown");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Willkommen</title>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <nav>
        <h1>Willkommen</h1>
    </nav>

    <h4 style="color: green; margin-left: 50px;">Vielen Dank, dass Sie sich für unser Buchverwaltungstool entschieden
        haben</h4>
    <form method="post" style="margin-top: 50px; margin-left: 30px; width: 300px;">
        <h5>Unser datenhungriger Pinguin will jedoch zuerst etwas Wissen!</h5>
        <input name="master" type="password" placeholder="Ihr Masterpasswort:">
        <button type="submit" style="background-color: pink; margin-top: 10px;">Los Gehts!</button>
    </form>

</body>

</html>
