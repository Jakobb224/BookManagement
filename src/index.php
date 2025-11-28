<?php
$mysqli = new mysqli("db", "appuser", "apppass", "appdb");

if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

echo "MariaDB Verbindung erfolgreich!";
