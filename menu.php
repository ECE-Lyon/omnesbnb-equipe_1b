<?php
session_start();

// Définition de la langue par défaut
$langue = isset($_COOKIE['lang']) ? $_COOKIE['lang'] : 'fr';

// Définition des messages
$messages = [
    'fr' => [
        'bienvenue' =>'Abonnez-vous à la Newsletter pour recevoir les dernières informations par mail !',
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
    </head>

    <body>
        <header>
            <h1>OmnesBnB</h1>
        </header>

        <main>

        </main>

        <footer>

        </footer>
    </body>
</html>