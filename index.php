<?php
session_start();

// Changement de langue si l'utilisateur a cliqué
if (isset($_GET['lang'])) {
    $langue = $_GET['lang'];
    setcookie('lang', $langue, time() + (3600 * 24 * 30), "/"); // 30 jours
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

// Définition de la langue par défaut
$langue = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';

// Définition des messages
$langues_dispo = [
    'fr' => [

    ],
    'en' => [

    ],
    'de' => [

    ]
];

$annonces = [
    [
        "id" => 1,
        "img" => "images_annonce/annonce1.jpeg",
        "titre" => "Petite villa à Londres",
        "lieu" => "Londres, Angleterre",
        "personnes" => 4,
        "prix" => 120
    ],
    [
        "id" => 2,
        "img" => "images_annonce/annonce2.jpeg",
        "titre" => "Villa avec piscine",
        "lieu" => "Nice, France",
        "personnes" => 8,
        "prix" => 350
    ],
    [
        "id" => 3,
        "img" => "images_annonce/annonce3.jpeg",
        "titre" => "Appartement vu Vieux Lyon",
        "lieu" => "Lyon, France",
        "personnes" => 2,
        "prix" => 35
]
];

?>

<!DOCTYPE html>
<html lang="<?php echo $langue; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OmnesBnB/menu</title>
        <link rel="stylesheet" href="menu.css">
        <link rel="stylesheet" href="annonce.css">
        <script type="text/javascript" src="javascript/recherche_logement.js"></script>
        <script type="text/javascript" src="javascript/langue_accordeon.js"><</script>
        <script type="text/javascript" src="javascript/animation_menu.js"><</script>
    </head>

    <body>
        <header>
            <section>

                <div class="header-container">


                    <div class="langue-accordeon">
                        <button id="selectlangue" class="select-langue">
                            <img src="images/<?php echo $langue; ?>.png" alt="<?php echo $langue; ?>" class="langue-icone">
                        </button>
                        <div id="langues-options" class="langues-options hidden">
                            <?php foreach ($langues_dispo as $code => $nom):
                                if ($code != $langue): ?>
                                    <a href="?lang=<?php echo $code; ?>">
                                        <img src="images/<?php echo $code; ?>.png" alt="<?php echo $nom; ?>" class="langue-icone">
                                        <?php echo $nom; ?>
                                    </a>
                                <?php endif; endforeach; ?>
                        </div>
                    </div>
                    <button onclick="window.location.href='index.php'" class="header-button logo-button">
                        <img src="images/logo_omnesBNB_noir.png" alt="Logo OmnesBNB" class="logo-img">
                        <h1>OmnesBnB</h1>
                    </button>
                    <?php if (isset($_SESSION['id'])): ?>
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
                <button onclick="window.location.href='location.php'" class="button-choix">
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


        <main>

            <section class="logement-recommander">
                <p class="titre-recommander">Logements recommandés de la semaine 🏆 </p>
                <div class="bloc-recommander">
                    <?php foreach ($annonces as $annonce): ?>
                        <button onclick="window.location.href='detail_annonce.php?id=<?= $annonce['id'] ?>'" class="annonce-bloc">
                            <img src="<?= $annonce['img'] ?>" alt="<?= $annonce['titre'] ?>" class="annonce-image">
                            <div class="annonce-details">
                                <h3 class="annonce-titre"><?= $annonce['titre'] ?></h3>
                                <p class="annonce-lieu"><?= $annonce['lieu'] ?></p>
                                <p class="annonce-personnes">👥 <?= $annonce['personnes'] ?> personnes</p>
                                <p class="annonce-prix"><?= $annonce['prix'] ?>€ / nuit</p>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>

            </section>

            <section class="info-omnes hidden-on-load">
                <div class="info-block">
                    <div class="info-text">OmnesBnB c'est quoi ?</div>
                    <div class="info-img">
                        <img src="images/logo_omnesBNB_blanc.png" alt="Logo OmnesBnB">
                    </div>
                </div>
                <div class="info-block reverse">
                    <div class="info-text">Permet aux utilisateurs de découvrir le monde entier.</div>
                    <div class="info-img">
                        <img src="images/logo_planete.png" alt="Découvrir le monde">
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-text">Publier votre logement en un claquement de doigt.</div>
                    <div class="info-img">
                        <img src="images/logo_logement_rapide.png" alt="Publication rapide">
                    </div>
                </div>
                <div class="info-block reverse">
                    <div class="info-text">Un mode de paiement sûr et rapide !</div>
                    <div class="info-img">
                        <img src="images/logo_paiement.png" alt="Paiement sécurisé">
                    </div>
                </div>
            </section>

        </main>

        <footer>
            <div class="footer-content">
                <p>© 2025 OmnesBnB. Tous droits réservés.</p>
            </div>
        </footer>

    </body>

</html>