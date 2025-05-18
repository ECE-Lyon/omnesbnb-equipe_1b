<?php
session_start();
require_once 'base_donnee.php';

// 👮‍♂️ Liste des IDs autorisés en admin (à adapter selon ton équipe)
$admins = [1, 3, 5]; // Exemples : Tao, Julian, Mathis

if (!isset($_SESSION['id']) || !in_array($_SESSION['id'], $admins)) {
    header('Location: index.php');
    exit();
}

$view = $_GET['view'] ?? 'utilisateurs';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panel Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="messagerie.css">
</head>
<body>
<header class="header-messages">
    <a href="index.php" class="btn-retour">‹</a>
    <h2>Panneau d'administration</h2>
</header>

<nav style="display: flex; justify-content: center; gap: 20px; margin: 1rem;">
    <a href="?view=utilisateurs">Utilisateurs</a>
    <a href="?view=logements">Logements</a>
    <a href="?view=reservations">Réservations</a>
</nav>

<main style="padding: 0 16px;">
    <?php if ($view === 'utilisateurs'): ?>
        <h3>Gestion des utilisateurs</h3>
        <!-- Tu ajouteras ici un tableau -->
    <?php elseif ($view === 'logements'): ?>
        <h3>Gestion des logements</h3>
        <!-- Tu ajouteras ici un tableau -->
    <?php elseif ($view === 'reservations'): ?>
        <h3>Gestion des réservations</h3>
        <!-- Tu ajouteras ici un tableau -->
    <?php else: ?>
        <p>Vue inconnue.</p>
    <?php endif; ?>
</main>
</body>
</html>
