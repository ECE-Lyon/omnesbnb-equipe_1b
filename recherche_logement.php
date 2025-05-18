<?php
session_start();

// Définition de la langue par défaut
$langue = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';

// Définition des messages
$messages = [
    'fr' => [

    ]
];

?>

<!DOCTYPE html>
<html lang="<?php echo $langue; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OmnesBnB/recherche_logement</title>
        <link rel="stylesheet" href="recherche_logement.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <script type="text/javascript" src="javascript/recherche_logement.js"></script>

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
                    <?php if (isset($_SESSION['user_id'])): ?>
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

        <main>

            <section class="recherche-logement">

                <div class="search-block">
                    <!-- Barre de recherche -->
                    <div class="search-container">
                        <button class="filtre-btn" onclick="toggleFilters()">☰</button>
                        <input type="text" id="searchInput" placeholder="Destination (Ville)">
                        <button class="search-btn" id="filtrer-btn">🔍</button>
                    </div>

                    <!-- Filtres en dessous -->
                    <div id="filtres" class="filtres hidden">
                        <!-- Dates -->
                        <div class="filtre-group">
                            <label for="start-date">Date d'arrivée :</label>
                            <input type="date" id="start-date">
                        </div>

                        <div class="filtre-group">
                            <label for="end-date">Date de départ :</label>
                            <input type="date" id="end-date">
                        </div>

                        <!-- Voyageurs -->
                        <div class="filtre-group">
                            <label for="voyageurs">Voyageurs :</label>
                            <input type="number" id="voyageurs" min="1" value="2" max="16">
                        </div>

                        <!-- Type de logement -->
                        <div class="filtre-group">
                            <label>Type de logement :</label>
                            <div class="type-logement">
                                <div class="type-option" onclick="toggleTypeOption(this)">
                                    <input type="radio" name="type-logement" id="logement-entier" checked>
                                    <label for="logement-entier">Logement entier</label>
                                </div>
                                <div class="type-option" onclick="toggleTypeOption(this)">
                                    <input type="radio" name="type-logement" id="colocation">
                                    <label for="colocation">Colocation</label>
                                </div>
                            </div>
                        </div>

                        <!-- Prix maximum par nuit -->
                        <div class="filtre-group">
                            <label for="prix-max">Prix maximum par nuit :</label>
                            <div class="slider-container">
                                <input type="range" id="prix-max" min="0" max="1000" value="200" class="range-slider" oninput="updatePrixValue(this.value)">
                                <div class="price-range">
                                    <span>0 €</span>
                                    <span>1000 €</span>
                                </div>
                                <div class="slider-value" id="prix-value">200 €</div>
                            </div>
                        </div>

                        <!-- Surface minimum -->
                        <div class="filtre-group">
                            <label for="surface-min">Surface minimum :</label>
                            <div class="slider-container">
                                <input type="range" id="surface-min" min="0" max="200" value="30" class="range-slider" oninput="updateSurfaceValue(this.value)">
                                <div class="surface-range">
                                    <span>0 m²</span>
                                    <span>200 m²</span>
                                </div>
                                <div class="slider-value" id="surface-value">30 m²</div>
                            </div>
                        </div>

                        <!-- Bouton d'application -->
                        <button class="appliquer-btn" onclick="appliquerFiltres()">Appliquer les filtres</button>
                    </div>
                </div>
            </section>

            <div id="resultats" class="bloc-resultat">
                <!-- Les cartes de logement s’afficheront ici dynamiquement -->
            </div>

        </main>

        <footer>
            <div class="footer-content">
                <p>© 2025 OmnesBnB. Tous droits réservés.</p>
            </div>
        </footer>

    </body>
</html>