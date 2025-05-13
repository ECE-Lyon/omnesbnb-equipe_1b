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
        <title>OmnesBnB/menu</title>
        <link rel="stylesheet" href="menu.css">
        <script type="text/javascript" src="../recherche_logement.js"></script>
    </head>

    <body>
        <header>
            <section>
                <div class="header-container">
                    <button id="menuToggle" class="header-button">☰</button>
                    <button onclick="window.location.href='menu.php'" class="header-button logo-button">
                        <img src="../images/logo_omnesBNB_noir.png" alt="Logo OmnesBNB" class="logo-img">
                        <h1>OmnesBnB</h1>
                    </button>
                    <button onclick="window.location.href='../connexion/login.php'" class="header-button">+</button>
                </div>
            </section>

        </header>

        <nav>
            <div class="choix-menu">
                <button onclick="window.location.href='../location.php'" class="button-choix">
                    <img src="../images/icon_reservation.png" alt="logo réservation" class="logo-choix-reservation">
                    <p>Réservation</p>
                </button>
                <button onclick="window.location.href='../recherche_logement.php'" class="button-choix">
                    <img src="../images/icon_recherche.png" alt="logo recherche" class="logo-choix-recherche">
                    <p>Rechercher</p>
                </button>
                <button onclick="window.location.href='menu.php'" class="button-choix">
                    <img src="../images/logo_omnesBNB_blanc.png" alt="Logo OmnesBnB" class="logo-choix-omnes">
                    <p>Accueil</p>
                </button>
                <button onclick="window.location.href='../publier.php'" class="button-choix">
                    <img src="../images/icon_publier.png" alt="logo publier" class="logo-choix-publier">
                    <p>Publier</p>
                </button>
                <button onclick="window.location.href='../messagerie.php'" class="button-choix">
                    <img src="../images/icon_message.png" alt="logo message" class="logo-choix-messagerie">
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
                        <input type="text" id="searchInput" placeholder="Destination">
                        <button class="search-btn" onclick="window.location.href='../recherche_logement.php'">🔍</button>
                    </div>

                    <!-- Filtres en dessous -->
                    <div id="filtres" class="filtres hidden">
                        <div class="filtre-group">
                            <label for="start-date">Date de début :</label>
                            <input type="date" id="start-date">
                        </div>

                        <div class="filtre-group">
                            <label for="end-date">Date de fin :</label>
                            <input type="date" id="end-date">
                        </div>

                        <div class="filtre-group">
                            <label for="voyageurs">Voyageurs :</label>
                            <input type="number" id="voyageurs" min="1" value="1">
                        </div>
                    </div>

                </div>
            </section>

            <section class="choix-utilisateur">

            </section>

            <section class="info-omnes">

            </section>

        </main>

        <footer>

        </footer>

    </body>
</html>