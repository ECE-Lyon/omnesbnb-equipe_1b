<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte - OmnesBnB</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Créer un compte</h1>

<form action="verif_inscription.php" method="post">
    <input type="text" name="prenom" placeholder="Prénom" required><br>
    <input type="text" name="nom" placeholder="Nom" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="mdp" placeholder="Mot de passe" required><br>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>
