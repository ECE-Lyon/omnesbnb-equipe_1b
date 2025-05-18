<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php?redirect=nouvelle_conversation.php");
    exit();
}

$mon_id = $_SESSION['id'];

$utilisateurs = $bdd->prepare("SELECT id, prenom, nom FROM utilisateursTest WHERE id != ?");
$utilisateurs->execute([$mon_id]);
$liste = $utilisateurs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle conversation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
<header class="header-messages">
    <a href="messagerie.php" class="btn-retour">‹</a>
    <h2>Nouvelle conversation</h2>
</header>

<main>
    <ul class="liste-utilisateurs">
        <?php foreach ($liste as $u): ?>
            <li class="utilisateur" data-id="<?= $u['id'] ?>">
                <strong><?= htmlspecialchars($u['prenom'] . " " . $u['nom']) ?></strong>
                <div class="verif-email" style="display: none;">
                    <input type="email" placeholder="Entrez son email">
                    <button class="btn-verif">Vérifier</button>
                    <p class="msg-erreur" style="color:red; display:none;"></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</main>

<script src="javascript/nouvelle_conversation.js"></script>
</body>
</html>
