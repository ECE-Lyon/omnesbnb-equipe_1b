<?php
session_start();
require_once 'base_donnee.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$id_utilisateur = $_SESSION['id'];

// Récupérer tous les favoris de l'utilisateur sans doublons
$stmt = $bdd->prepare("
    SELECT DISTINCT f.id_logement, l.titre, l.ville, l.pays, l.prix_par_personne, l.type_location, l.surfaces, l.places, l.photo_principale
    FROM favoris f
    JOIN logements l ON f.id_logement = l.id
    WHERE f.id_utilisateur = ?
    ORDER BY f.id_logement
");
$stmt->execute([$id_utilisateur]);
$favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris</title>
    <link rel="stylesheet" href="my_listings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="javascript/recherche_logement.js" defer></script>
</head>
<body>
<div class="listings-container">
    <h1>Mes Favoris ❤️</h1>

    <?php if (empty($favoris)): ?>
        <p class="no-result">Vous n'avez pas encore de favoris.</p>
    <?php else: ?>
        <?php foreach ($favoris as $favori): ?>
            <div class="listing-card" id="favori-<?= $favori['id_logement'] ?>">
                <img src="<?= $favori['photo_principale'] ?: 'default.jpg' ?>" alt="<?= $favori['titre'] ?>" class="listing-photo">
                <div class="listing-details">
                    <h3><?= htmlspecialchars($favori['titre']) ?></h3>
                    <p><?= htmlspecialchars($favori['ville']) ?>, <?= htmlspecialchars($favori['pays']) ?></p>
                    <p><?= $favori['type_location'] === 'colocation' ? 'Colocation' : 'Logement entier' ?></p>
                    <p><?= $favori['surfaces'] ?> m² · <?= $favori['places'] ?> voyageurs</p>
                    <p class="prix"><?= number_format($favori['prix_par_personne'], 2) ?> € par personne et par nuit</p>
                </div>
                <div class="favoris-icon" onclick="retirerFavori(<?= $favori['id_logement'] ?>)">
                    <i class="fa-solid fa-heart"></i>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="compte.php" class="return-link">← Retour à mon compte</a>
</div>
</body>
</html>