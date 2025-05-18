

<?php
session_start();
// Récupération de l'id passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$id = intval($_GET['id']);

// Connexion à la base de données
require_once 'base_donnee.php';
// base_donnee.php doit exposer un objet PDO nommé $pdo
if (!isset($pdo) || !($pdo instanceof PDO)) {
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=omnesbnb;charset=utf8mb4', 'db_user', 'db_pass', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        die('Erreur de connexion BDD : ' . $e->getMessage());
    }
}

// Récupération des données de l'annonce
$stmt = $bdd->prepare(
    'SELECT l.*, u.pseudo, u.avatar
     FROM logements l
     JOIN users u ON u.id = l.id_utilisateur
     WHERE l.id = :id'
);
$stmt->execute([':id' => $id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($annonce['titre']) ?> - OmnesBnB</title>
    <link rel="stylesheet" href="annonceclick.css">
</head>
<body>
<a href="javascript:history.back()" class="btn-back">← Retour</a>
<main class="ad-container">
    <div class="ad-gallery">
        <img src="<?= htmlspecialchars($annonce['photo_principale']) ?>" alt="Photo annonce" class="ad-image">
        <button class="btn-fav" aria-label="Favoris">♥</button>
    </div>
    <section class="ad-details">
        <h1 class="ad-title"><?= htmlspecialchars($annonce['titre']) ?></h1>
        <p class="ad-location">📍 <?= htmlspecialchars($annonce['adresse']) ?></p>
        <p class="ad-price">💶 <?= htmlspecialchars($annonce['prix_par_personne']) ?>€ / nuit</p>
        <div class="ad-description">
            <h2>Description</h2>
            <p><?= nl2br(htmlspecialchars($annonce['description'])) ?></p>
        </div>
        <div class="ad-owner">
            <img src="<?= htmlspecialchars($annonce['avatar'] ?? 'default-avatar.png') ?>" alt="Avatar" class="owner-avatar">
            <p class="owner-name"><?= htmlspecialchars($annonce['pseudo']) ?></p>
        </div>
        <div class="ad-map">
            <iframe
                src="https://www.google.com/maps?q=<?= urlencode($annonce['adresse']) ?>&output=embed"
                allowfullscreen loading="lazy">
            </iframe>
        </div>
    </section>
</main>
<footer class="site-footer">
    <nav>
        <ul>
            <li><a href="#">Qui sommes-nous ?</a></li>
            <li><a href="#">Comment ça marche ?</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
</footer>
</body>
</html>

