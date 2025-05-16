<?php
require_once("base_donnee.php");

$prenom = htmlspecialchars($_POST['prenom']);
$nom = htmlspecialchars($_POST['nom']);
$email = htmlspecialchars($_POST['email']);
$mdp = $_POST['mdp'];

// Hacher le mot de passe avant de le stocker
$mdp_hache = password_hash($mdp, PASSWORD_DEFAULT);

// Changement de la table utilisateurs à utilisateursTest
$requete = $bdd->prepare("INSERT INTO utilisateursTest (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
$requete->execute([$prenom, $nom, $email, $mdp_hache]);

header("Location: login.php");
exit();
?>