<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - OmnesBnB</title>
    <link rel="stylesheet" href="connexion1.css">
</head>
<body>
<h1>Connexion à OmnesBnB</h1>
<br>

<form action="verif_login.php" method="post">
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="mdp" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>

<p><a href="inscription.php" style="color: blue;">Créer un compte</a></p>

<?php if (isset($_SESSION['erreur'])) { echo "<p class='erreur'>" . $_SESSION['erreur'] . "</p>"; unset($_SESSION['erreur']); } ?>
</body>
</html>
