<?php
session_start();
if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit();
}

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="connexion1.css">
</head>
<body>
<div class="container">
    <h2>Connexion à OmnesBnB</h2>
    <form method="post" action="verif_login.php<?= $redirect ? '?redirect=' . urlencode($redirect) : '' ?>">
        <label for="email">Email :</label>
        <input type="email" name="email" required>

        <label for="mdp">Mot de passe :</label>
        <input type="password" name="mdp" required> <!-- ✅ nom du champ corrigé ici -->

        <button type="submit">Se connecter</button>
    </form>

    <p>Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
</div>
</body>
</html>
