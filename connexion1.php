<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="connexion1.css">
</head>
<body>
<!-- Status bar -->
</header>

<!-- Main login content -->
<main class="login-container">
    <h1>Connexion</h1>

    <form action="#" class="login-form">
        <label>
            Username / Email
            <input type="text" name="username" placeholder="Entrez votre email">
        </label>

        <label>
            Mot de passe
            <input type="password" name="password" placeholder="Entrez votre mot de passe">
        </label>

        <div class="links">
            <a href="#">Créer un compte</a>
            <a href="#">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="btn-primary">
            Se connecter
            <span class="material-symbols-lock">lock</span>
        </button>
    </form>

    <div class="social-login">
        <button class="btn-social google">
            <img src="icons/google.svg" alt=""> Google
        </button>
        <button class="btn-social microsoft">
            <img src="icons/microsoft.svg" alt=""> Microsoft
        </button>
        <button class="btn-social linkedin">
            <img src="icons/linkedin.svg" alt=""> LinkedIn
        </button>
        <button class="btn-social twitter">
            <img src="icons/twitter.svg" alt=""> Twitter
        </button>
        <button class="btn-social facebook">
            <img src="icons/facebook.svg" alt=""> Facebook
        </button>
        <button class="btn-social apple">
            <img src="icons/apple.svg" alt=""> Apple
        </button>
    </div>
</main>

<!-- Footer -->
<footer class="site-footer">
    <nav>
        <ul>
            <li><a href="#">Qui sommes-nous ?</a></li>
            <li><a href="#">Comment ça marche ?</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
    <div class="logo">
        <img src="omnes-logo.jpg" alt="OMNES Logo">
    </div>
    <div class="footer-bar"></div>
</footer>
</body>
</html>
<?php
