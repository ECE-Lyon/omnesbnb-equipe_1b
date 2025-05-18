<?php
session_start();
require_once 'base_donnee.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $motdepasse = $_POST['mdp'] ?? ''; // ✅ bon nom du champ

    if (empty($email) || empty($motdepasse)) {
        header('Location: login.php?erreur=champs_vides');
        exit();
    }

    // ✅ Utilisation de la bonne table
    $stmt = $bdd->prepare("SELECT * FROM utilisateursTest WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($motdepasse, $utilisateur['mot_de_passe'])) {
        $_SESSION['id'] = $utilisateur['id'];

        if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
            header('Location: ' . $_GET['redirect']);
        } else {
            header('Location: index.php');
        }
        exit();
    } else {
        $redirect = isset($_GET['redirect']) ? '&redirect=' . urlencode($_GET['redirect']) : '';
        header('Location: login.php?erreur=1' . $redirect);
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
