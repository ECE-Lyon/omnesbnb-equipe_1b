<?php
try {
    // Paramètres de connexion pour un environnement local (ex: XAMPP, WAMP)
    $host = 'localhost';
    $nom_base = 'omnesbnb'; // même nom que ta base importée localement
    $utilisateur = 'root'; // utilisateur local par défaut
    $mot_de_passe = ''; // mot de passe vide par défaut

    $bdd = new PDO(
        'mysql:host=' . $host . ';dbname=' . $nom_base . ';charset=utf8',
        $utilisateur,
        $mot_de_passe,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>