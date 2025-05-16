<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un compte - OmnesBnB</title>
    <link rel="stylesheet" href="inscription.css">
</head>
<body>
<a href="login.php" class="btn-back" aria-label="Retour">&times;</a>

<h1>Créer un compte</h1>
<BR>

<form action="verif_inscription.php" method="post">
    <input type="text" name="prenom" placeholder="Prénom" required><br>
    <input type="text" name="nom" placeholder="Nom" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="mdp" placeholder="Mot de passe" required><br>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>
