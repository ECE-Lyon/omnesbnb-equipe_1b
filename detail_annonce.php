<?php
session_start();
require_once 'base_donnee.php';

// Vérifier si l'ID du logement est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: recherche_logement.php');
    exit;
}

$id_logement = intval($_GET['id']);

// Récupérer les détails du logement
$stmt = $bdd->prepare("
    SELECT l.*, u.prenom, u.nom, u.email, u.telephone
    FROM logements l
    JOIN utilisateursTest u ON l.id_utilisateur = u.id
    WHERE l.id = ?
");
$stmt->execute([$id_logement]);
$logement = $stmt->fetch(PDO::FETCH_ASSOC);

// Si le logement n'existe pas, rediriger vers la page de recherche
if (!$logement) {
    header('Location: recherche_logement.php');
    exit;
}

// Vérifier si l'utilisateur est connecté
$est_connecte = isset($_SESSION['id']);
$est_proprietaire = $est_connecte && $_SESSION['id'] == $logement['id_utilisateur'];

// Vérifier si le logement est en favoris
$est_favori = false;
if ($est_connecte) {
    $stmt = $bdd->prepare("SELECT COUNT(*) FROM favoris WHERE id_utilisateur = ? AND id_logement = ?");
    $stmt->execute([$_SESSION['id'], $id_logement]);
    $est_favori = $stmt->fetchColumn() > 0;
}

// Récupérer les images supplémentaires du logement
$stmt = $bdd->prepare("SELECT photo_principale FROM logements WHERE id = ?");
$stmt->execute([$id_logement]);
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Ajouter l'image principale au début du tableau d'images
if (!empty($logement['photo_principale'])) {
    array_unshift($images, $logement['photo_principale']);
} else {
    array_unshift($images, 'default.jpg');
}

// Formater le prix
$prix = number_format($logement['prix_par_personne'], 2, ',', ' ');


$langue = isset($_SESSION['langue']) ? $_SESSION['langue'] : 'fr';
?>

<!DOCTYPE html>
<html lang="<?php echo $langue; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OmnesBnB - <?php echo htmlspecialchars($logement['titre']); ?></title>
    <link rel="stylesheet" href="recherche_logement.css">
    <link rel="stylesheet" href="detail_annonce.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="javascript/detail_annonce.js" defer></script>
</head>

<body>
<header>
    <section>
        <div class="header-container">
            <div class="langue-accordeon">
                <button id="selectlangue" class="select-langue">
                    <img src="images/fr.png" alt="fr" class="langue-icone">
                </button>
            </div>
            <button onclick="window.location.href='index.php'" class="header-button logo-button">
                <img src="images/logo_omnesBNB_noir.png" alt="Logo OmnesBNB" class="logo-img">
                <h1>OmnesBnB</h1>
            </button>
            <?php if ($est_connecte): ?>
                <!-- Bouton Profil visible après connexion -->
                <button onclick="window.location.href='compte.php'" class="header-button" title="Voir mon profil">👤</button>
            <?php else: ?>
                <!-- Bouton + pour se connecter -->
                <button onclick="window.location.href='login.php'" class="header-button" title="Se connecter">+</button>
            <?php endif; ?>
        </div>
    </section>
</header>

<nav>
    <div class="choix-menu">
        <button onclick="window.location.href='../location.php'" class="button-choix">
            <img src="images/icon_reservation.png" alt="logo réservation" class="logo-choix-reservation">
            <p>Réservation</p>
        </button>
        <button onclick="window.location.href='recherche_logement.php'" class="button-choix">
            <img src="images/icon_recherche.png" alt="logo recherche" class="logo-choix-recherche">
            <p>Rechercher</p>
        </button>
        <button onclick="window.location.href='index.php'" class="button-choix">
            <img src="images/logo_omnesBNB_blanc.png" alt="Logo OmnesBnB" class="logo-choix-omnes">
            <p>Accueil</p>
        </button>
        <button onclick="window.location.href='publier_logement.php'" class="button-choix">
            <img src="images/icon_publier.png" alt="logo publier" class="logo-choix-publier">
            <p>Publier</p>
        </button>
        <button onclick="window.location.href='messagerie.php'" class="button-choix">
            <img src="images/icon_message.png" alt="logo message" class="logo-choix-messagerie">
            <p>Message</p>
        </button>
    </div>
</nav>

<main class="detail-logement">
    <!-- Bouton retour -->
    <div class="back-button">
        <a href="recherche_logement.php"><i class="fas fa-arrow-left"></i> Retour à la recherche</a>
    </div>

    <!-- Titre et favoris -->
    <div class="details-header">
        <h1><?php echo htmlspecialchars($logement['titre']); ?></h1>
        <?php if ($est_connecte && !$est_proprietaire): ?>
            <div class="favoris-icon detail-favoris" data-id="<?php echo $id_logement; ?>" onclick="toggleFavori(event, <?php echo $id_logement; ?>)">
                <i class="fa-heart <?php echo $est_favori ? 'fa-solid' : 'fa-regular'; ?>"></i>
            </div>
        <?php endif; ?>
    </div>

    <!-- Information de localisation -->
    <div class="details-location">
        <i class="fas fa-map-marker-alt"></i>
        <span><?php echo htmlspecialchars($logement['adresse']); ?>, <?php echo htmlspecialchars($logement['ville']); ?>, <?php echo htmlspecialchars($logement['pays']); ?></span>
    </div>

    <!-- Gallerie d'images -->
    <div class="image-gallery">
        <div class="main-image">
            <img src="<?php echo htmlspecialchars($images[0]); ?>" alt="<?php echo htmlspecialchars($logement['titre']); ?>" id="mainImage">
        </div>
        <?php if (count($images) > 1): ?>
            <div class="thumbnails">
                <?php foreach ($images as $index => $image): ?>
                    <img src="<?php echo htmlspecialchars($image); ?>"
                         alt="Photo <?php echo $index + 1; ?>"
                         onclick="changeMainImage('<?php echo htmlspecialchars($image); ?>')"
                         class="<?php echo $index === 0 ? 'active' : ''; ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="details-content">
        <!-- Informations principales -->
        <div class="details-info">
            <div class="info-section">
                <h2>Informations générales</h2>
                <p class="type-logement">
                    <i class="fas fa-home"></i>
                    <?php echo $logement['type_location'] === 'colocation' ? 'Colocation' : 'Logement entier'; ?>
                </p>
                <p class="capacity">
                    <i class="fas fa-users"></i>
                    <?php echo $logement['places']; ?> voyageurs
                </p>
                <p class="surface">
                    <i class="fas fa-expand"></i>
                    <?php echo $logement['surfaces']; ?> m²
                </p>
                <?php if (!empty($logement['equipements'])): ?>
                    <div class="equipements">
                        <h3>Équipements</h3>
                        <ul>
                            <?php
                            $equipements = explode(',', $logement['equipements']);
                            foreach ($equipements as $equipement): ?>
                                <li><i class="fas fa-check"></i> <?php echo htmlspecialchars(trim($equipement)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>

            <div class="info-section">
                <h2>Description</h2>
                <p class="description">
                    <?php echo nl2br(htmlspecialchars($logement['description'])); ?>
                </p>
            </div>

            <div class="info-section">
                <h2>À propos du propriétaire</h2>
                <p class="owner-info">
                    <i class="fas fa-user"></i>
                    <?php echo htmlspecialchars($logement['prenom'] . ' ' . $logement['nom']); ?>
                </p>
                <?php if (!$est_proprietaire): ?>
                    <button class="contact-btn" onclick="window.location.href = 'messagerie.php'">
                        <i class="fas fa-envelope"></i>
                        Contacter (<?php echo htmlspecialchars($logement['email']); ?>)
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Formulaire de réservation -->
        <div class="booking-section">
            <div class="booking-card">
                <h2>Réserver</h2>
                <p class="prix-nuit"><?php echo $prix; ?> € <span>par personne et par nuit</span></p>

                <?php if ($est_connecte && !$est_proprietaire): ?>
                    <form id="booking-form" action="reserver_logement.php" method="post">
                        <input type="hidden" name="id_logement" value="<?php echo $id_logement; ?>">

                        <div class="form-group">
                            <label for="date_arrivee">Date d'arrivée</label>
                            <input type="date" id="date_arrivee" name="date_arrivee" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="date_depart">Date de départ</label>
                            <input type="date" id="date_depart" name="date_depart" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>

                        <div class="form-group">
                            <label for="nb_voyageurs">Nombre de voyageurs</label>
                            <input type="number" id="nb_voyageurs" name="nb_voyageurs" min="1" max="<?php echo $logement['places']; ?>" value="1" required>
                        </div>

                        <div class="reservation-summary">
                            <div class="reservation-row">
                                <span>Prix par nuit et par personne</span>
                                <span><?php echo $prix; ?> €</span>
                            </div>
                            <div class="reservation-row" id="total-nights">
                                <span>Nombre de nuits</span>
                                <span>-</span>
                            </div>
                            <div class="reservation-row" id="total-persons">
                                <span>Nombre de personnes</span>
                                <span>1</span>
                            </div>
                            <div class="reservation-total">
                                <span>Total</span>
                                <span id="prix-total">-</span>
                            </div>
                        </div>

                        <button type="submit" class="reserver-btn">Réserver</button>
                    </form>
                <?php elseif (!$est_connecte): ?>
                    <div class="login-prompt">
                        <p>Connectez-vous pour réserver ce logement</p>
                        <a href="login.php" class="login-btn">Se connecter</a>
                    </div>
                <?php else: ?>
                    <div class="owner-notice">
                        <p>Vous êtes le propriétaire de ce logement</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<footer>
    <div class="footer-content">
        <p>© 2025 OmnesBnB. Tous droits réservés.</p>
    </div>
</footer>
</body>
</html>