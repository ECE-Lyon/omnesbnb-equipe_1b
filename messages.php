<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php?redirect=messagerie.php');
    exit();
}

$mon_id = $_SESSION['id'];

// ID du destinataire dans l'URL
$autre_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($autre_id <= 0) {
    echo "Conversation invalide.";
    exit();
}

// Récup info utilisateur destinataire
$stmt = $bdd->prepare("SELECT nom, prenom, photo_profil FROM utilisateursTest WHERE id = ?");
$stmt->execute([$autre_id]);
$autre = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$autre) {
    echo "Utilisateur introuvable.";
    exit();
}

// Marquer tous les messages reçus comme lus
$bdd->prepare("UPDATE messages SET lu = 1 WHERE expediteur_id = ? AND destinataire_id = ?")
    ->execute([$autre_id, $mon_id]);

// Récupérer tous les messages entre les deux
$messages = $bdd->prepare("
    SELECT * FROM messages
    WHERE (expediteur_id = :me AND destinataire_id = :other)
       OR (expediteur_id = :other AND destinataire_id = :me)
    ORDER BY date_envoi ASC
");
$messages->execute(['me' => $mon_id, 'other' => $autre_id]);
$liste_messages = $messages->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Conversation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
<header class="header-messages">
    <a href="messagerie.php" class="btn-retour">‹</a>
    <div class="profil-messages">
        <img src="<?= htmlspecialchars($autre['photo_profil'] ?? 'images/default.jpg') ?>" alt="">
        <span><?= htmlspecialchars($autre['prenom'] . " " . $autre['nom']) ?></span>
    </div>
</header>

<main class="conversation">
    <?php foreach ($liste_messages as $msg): ?>
        <div class="<?= $msg['expediteur_id'] == $mon_id ? 'msg-moi' : 'msg-lui' ?>">
            <p><?= htmlspecialchars($msg['message']) ?></p>
            <small><?= date('d/m/Y H:i', strtotime($msg['date_envoi'])) ?></small>
        </div>
    <?php endforeach; ?>
</main>

<form class="form-message" action="envoyer_message.php" method="post">
    <input type="hidden" name="destinataire_id" value="<?= $autre_id ?>">
    <textarea name="message" placeholder="Écris ton message..." required></textarea>
    <button type="submit">Envoyer</button>
</form>
</body>
</html>
