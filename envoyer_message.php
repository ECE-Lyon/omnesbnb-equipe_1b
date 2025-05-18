<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$expediteur_id = $_SESSION['id'];
$destinataire_id = isset($_POST['destinataire_id']) ? intval($_POST['destinataire_id']) : 0;
$message = trim($_POST['message'] ?? '');

if ($destinataire_id <= 0 || $message === '') {
    header('Location: messagerie.php');
    exit();
}

// Vérification que l'utilisateur destinataire existe bien
$stmt = $bdd->prepare("SELECT id FROM utilisateursTest WHERE id = ?");
$stmt->execute([$destinataire_id]);
if (!$stmt->fetch()) {
    echo "Destinataire introuvable.";
    exit();
}

// Insertion du message
$insert = $bdd->prepare("INSERT INTO messages (expediteur_id, destinataire_id, message) VALUES (?, ?, ?)");
$insert->execute([$expediteur_id, $destinataire_id, $message]);

// Redirection vers la conversation
header("Location: messages.php?id=" . $destinataire_id);
exit();
