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
        <script type="text/javascript" src="javascript/recherche_logement.js"></script>
    </head>

    <body>
        <header>
            <div class="header-container">
                <button id="menuToggle" class="header-button">☰</button>
                <button onclick="window.location.href='index.php'" class="header-button"><h1>OmnesBnB</h1></button>
                <button onclick="window.location.href='login.php'" class="header-button">+</button>
            </div>

            <!-- Menu déroulant caché -->
            <nav id="dropdownMenu" class="hidden-menu">
                <ul>
                    <li><a href="#">Accueil</a></li>
                    <li><a href="#">Mes réservations</a></li>
                    <li><a href="#">Profil</a></li>
                    <li><a href="#">Déconnexion</a></li>
                </ul>
            </nav>

        </header>

        <main>

        </main>

        <footer>

        </footer>

    </body>
</html>