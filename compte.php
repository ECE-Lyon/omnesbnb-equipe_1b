<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les infos de l'utilisateur
$query = $bdd->prepare("SELECT * FROM utilisateursTest WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch();

if (!$user) {
    die("Utilisateur introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="compte.css">
</head>
<body>
<a href="index.php" class="close-button" title="Retour à l’accueil">✖</a>

<div class="profil-container">
    <h1>Bienvenue, <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>

    <?php if (!empty($user['photo_profil'])): ?>
        <img src="<?php echo htmlspecialchars($user['photo_profil']); ?>" alt="Photo de profil" class="profil-photo">
    <?php else: ?>
        <img src="images/default.jpg" alt="Profil par défaut" class="profil-photo">
    <?php endif; ?>


    <p class="profil-info"><strong>Email :</strong> <?php echo htmlspecialchars($user['email']); ?></p>

    <div class="profil-actions">
        <a href="edit_profile.php">Modifier mon profil</a>
        <a href="my_listings.php">Voir mes logements publiés</a>
        <a href="mes_favoris.php">Voir mes favoris ❤️</a>
        <a href="logout.php">Se déconnecter</a>


    </div>
</div>

<footer>
    <div class="footer-content">
        <p>© 2025 OmnesBnB. Tous droits réservés.</p>
    </div>
</footer>

</body>
</html>