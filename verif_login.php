<?php
session_start();
require_once("base_donnee.php");

$email = $_POST['email'];
$mdp = $_POST['mdp'];

// Changement de la table utilisateurs à utilisateursTest
$requete = $bdd->prepare("SELECT id, prenom, nom, email, mot_de_passe FROM utilisateursTest WHERE email = ?");
$requete->execute([$email]);
$utilisateur = $requete->fetch();

// Vérifier si l'utilisateur existe et si le mot de passe correspond
if ($utilisateur && password_verify($mdp, $utilisateur['mot_de_passe'])) {
    // Ne pas stocker le mot de passe dans la session
    $_SESSION['utilisateur'] = [
        'id' => $utilisateur['id'],
        'prenom' => $utilisateur['prenom'],
        'nom' => $utilisateur['nom'],
        'email' => $utilisateur['email']
    ];
    header("Location: index.php");
    exit();
} else {
    $_SESSION['erreur'] = "Identifiants incorrects.";
    header("Location: login.php");
    exit();
}
?>