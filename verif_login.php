<?php
session_start();
require_once("base_donnee.php");

$email = $_POST['email'];
$mdp = $_POST['mdp'];

$requete = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ? AND mot_de_passe = ?");
$requete->execute([$email, $mdp]);
$utilisateur = $requete->fetch();

if ($utilisateur) {
    $_SESSION['utilisateur'] = $utilisateur;
    header("Location: omnes.php");
    exit();
} else {
    $_SESSION['erreur'] = "Identifiants incorrects.";
    header("Location: index.php");
    exit();
}
