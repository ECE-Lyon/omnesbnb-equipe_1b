<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['destinataire_id'], $_POST['contenu']) || !is_numeric($_POST['destinataire_id'])) {
        die("Requête invalide.");
    }

    $expediteur_id = $_SESSION['id'];
    $destinataire_id = intval($_POST['destinataire_id']);
    $contenu = trim($_POST['contenu']);

    if ($contenu !== '') {
        $requete = $bdd->prepare("INSERT INTO messages (expediteur_id, destinataire_id, contenu) VALUES (?, ?, ?)");
        $requete->execute([$expediteur_id, $destinataire_id, $contenu]);
    }

    header("Location: messages.php?id=" . $destinataire_id);
    exit();
}
?>
