<?php
session_start();
session_unset();    // Supprime toutes les variables de session
session_destroy();  // Détruit la session

// Optionnel : supprimer aussi les cookies de session s’il y en a
setcookie(session_name(), '', time() - 3600, '/');

// Redirection vers la page d’accueil ou de login
header("Location: index.php");
exit();
?>

