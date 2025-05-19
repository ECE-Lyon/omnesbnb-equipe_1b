<?php
session_start();
require_once 'base_donnee.php';

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id'];

// Vérifie que l'utilisateur existe dans utilisateursTest
$query = $bdd->prepare("SELECT * FROM utilisateursTest WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch();

if (!$user) {
    die("Utilisateur introuvable.");
}

// Récupère les logements de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM logements WHERE id_utilisateur = ?");
$stmt->execute([$user_id]);
$logements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes logements publiés</title>
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="my_listings.css">

</head>
<body>
<div class="listings-container">
    <h1>Mes logements</h1>

    <?php if (count($logements) === 0): ?>
        <p>Vous n'avez publié aucun logement.</p>
    <?php else: ?>
        <?php foreach ($logements as $logement): ?>
            <div class="listing-card">
                <img src="<?php echo htmlspecialchars($logement['photo_principale']); ?>" alt="Photo" class="listing-photo">
                <div class="listing-details">
                    <h3><?php echo htmlspecialchars($logement['titre']); ?></h3>
                    <p><strong>Adresse :</strong> <?php echo htmlspecialchars($logement['adresse']); ?></p>
                    <p><strong>Ville/Pays :</strong> <?php echo htmlspecialchars($logement['ville']) . ', ' . htmlspecialchars($logement['pays']); ?></p>
                    <p><strong>Prix :</strong> <?php echo htmlspecialchars($logement['prix_par_personne']); ?> € / personne</p>
                    <p><strong>Type :</strong> <?php echo htmlspecialchars($logement['type_location']); ?> - <?php echo $logement['statut']; ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="compte.php" class="return-link">← Retour à mon compte</a>
</div>

</body>
</html>
