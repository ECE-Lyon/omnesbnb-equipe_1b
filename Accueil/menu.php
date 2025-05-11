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
            <div class="header-container">
                <button id="menuToggle" class="header-button">☰</button>
                <button onclick="window.location.href='menu.php'" class="header-button"><h1>OmnesBnB</h1></button>
                <button onclick="window.location.href='../connexion/login.php'" class="header-button">+</button>
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

            <main>
                <section class="recherche-logement">
                    <div class="search-container">

                        <!-- Bouton de filtres -->
                        <button class="filter-btn" onclick="toggleFilters()">☰</button>

                        <!-- Champ de destination -->
                        <input type="text" id="searchInput" onkeyup="rechercheLogement()" placeholder="Destination">

                        <!-- Bouton rechercher -->
                        <button class="search-btn" onclick="rechercheLogement()">Rechercher 🔍</button>
                    </div>

                    <!-- Filtres déroulants -->
                    <div id="filters" class="filters hidden">
                        <label for="start-date">Date de début :</label>
                        <input type="date" id="start-date">

                        <label for="end-date">Date de fin :</label>
                        <input type="date" id="end-date">

                        <label for="voyageurs">Voyageurs :</label>
                        <input type="number" id="voyageurs" min="1" value="1">
                    </div>
                </section>
            </main>


        </main>

        <footer>

        </footer>

        <script>
            function toggleFilters() {
                const filterSection = document.getElementById("filters");
                filterSection.classList.toggle("hidden");
            }
        </script>
    </body>
</html>