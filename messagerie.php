<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php?redirect=messagerie.php');
    exit();
}

$mon_id = $_SESSION['id'];

$profil = $bdd->prepare("SELECT prenom, nom FROM utilisateurs WHERE id = ?");
$profil->execute([$mon_id]);
$mon_profil = $profil->fetch(PDO::FETCH_ASSOC);

$requete = $bdd->prepare("
    SELECT u.id, u.nom, u.prenom,
           (SELECT message FROM messages m2 
            WHERE (m2.expediteur_id = :id AND m2.destinataire_id = u.id) 
               OR (m2.expediteur_id = u.id AND m2.destinataire_id = :id)
            ORDER BY date_envoi DESC
            LIMIT 1) AS dernier_message
    FROM utilisateurs u
    WHERE u.id != :id
    AND EXISTS (
        SELECT 1 FROM messages 
        WHERE (expediteur_id = :id AND destinataire_id = u.id)
           OR (expediteur_id = u.id AND destinataire_id = :id)
    )
    ORDER BY u.nom
");
$requete->execute(['id' => $mon_id]);
$conversations = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messagerie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
<header class="header-messages">
    <a href="index.php" class="btn-retour">‹</a>
    <div class="profil-messages">
        <img src="images/default.jpg" alt="Photo de profil">
        <span><?= htmlspecialchars($mon_profil['prenom'] . " " . $mon_profil['nom']) ?></span>
    </div>
</header>

<main>
    <?php if (empty($conversations)) : ?>
        <p class="no-conv">Vous n'avez encore aucune conversation.</p>
    <?php else : ?>
        <ul class="liste-conv">
            <?php foreach ($conversations as $conv) : ?>
                <li>
                    <a href="messages.php?id=<?= $conv['id'] ?>">
                        <strong><?= htmlspecialchars($conv['prenom'] . " " . $conv['nom']) ?></strong><br>
                        <em><?= htmlspecialchars($conv['dernier_message']) ?></em>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>
