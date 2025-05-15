<?php
try {
    $host = 'fdb1027.your-hosting.net';
    $nom_base = '4620382_omnesbnb';
    $utilisateur = '4620382_omnesbnb';
    $mot_de_passe = '%x0duQ@@90Z/sop-';

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
