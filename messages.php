<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$mon_id = $_SESSION['id'];

// Vérification de l'identifiant du destinataire
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Identifiant invalide.");
}

$autre_id = intval($_GET['id']);

// Récupérer les infos de l'autre utilisateur
$requeteUser = $bdd->prepare("SELECT nom, prenom FROM utilisateurs WHERE id = ?");
$requeteUser->execute([$autre_id]);
$autre = $requeteUser->fetch(PDO::FETCH_ASSOC);

if (!$autre) {
    die("Utilisateur introuvable.");
}

// Marquer les messages reçus comme lus
$updateLu = $bdd->prepare("UPDATE messages SET lu = 1 WHERE expediteur_id = ? AND destinataire_id = ?");
$updateLu->execute([$autre_id, $mon_id]);

// Récupérer tous les messages entre les deux utilisateurs
$requeteMessages = $bdd->prepare("
    SELECT * FROM messages
    WHERE (expediteur_id = :me AND destinataire_id = :other)
       OR (expediteur_id = :other AND destinataire_id = :me)
    ORDER BY date_envoi ASC
");
$requeteMessages->execute(['me' => $mon_id, 'other' => $autre_id]);
$messages = $requeteMessages->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Conversation avec <?= htmlspecialchars($autre['prenom'] . ' ' . $autre['nom']) ?></title>
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
<h1>Conversation avec <?= htmlspecialchars($autre['prenom'] . ' ' . $autre['nom']) ?></h1>

<div class="messages-zone">
    <?php if (empty($messages)) : ?>
        <p>Aucun message pour l’instant.</p>
    <?php else : ?>
        <?php foreach ($messages as $msg) : ?>
            <div style="text-align: <?= $msg['expediteur_id'] == $mon_id ? 'right' : 'left' ?>;">
                <p>
                    <strong><?= $msg['expediteur_id'] == $mon_id ? "Moi" : htmlspecialchars($autre['prenom']) ?> :</strong><br>
                    <?= nl2br(htmlspecialchars($msg['contenu'])) ?><br>
                    <small><?= $msg['date_envoi'] ?></small>
                </p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<form action="envoyer_message.php" method="post">
    <input type="hidden" name="destinataire_id" value="<?= $autre_id ?>">
    <textarea name="contenu" rows="4" cols="50" placeholder="Votre message..." required></textarea><br>
    <button type="submit">Envoyer</button>
</form>

<p><a href="messagerie.php">← Retour à la messagerie</a></p>
</body>
</html>
