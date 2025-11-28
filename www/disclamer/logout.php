<?php
session_start();
session_unset(); // Löscht alle Session-Daten
session_destroy(); // Beendet die Session
header("Location: ../index.php");
exit();
?>
