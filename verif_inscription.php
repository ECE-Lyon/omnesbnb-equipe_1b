<?php
require_once("base_donnee.php");

$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$email = $_POST['email'];
$mdp = $_POST['mdp'];

$requete = $bdd->prepare("INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
$requete->execute([$prenom, $nom, $email, $mdp]);

header("Location: login.php");
exit();
