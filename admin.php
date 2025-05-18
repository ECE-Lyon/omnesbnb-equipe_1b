<?php
session_start();
require_once 'base_donnee.php';

$admins = [1, 2, 3, 4];

if (!isset($_SESSION['id']) || !in_array($_SESSION['id'], $admins)) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer_utilisateur'])) {
        $id = intval($_POST['supprimer_utilisateur']);
        if (!in_array($id, $admins)) {
            $stmt = $bdd->prepare("DELETE FROM utilisateursTest WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: admin.php?view=utilisateurs');
        exit();
    }

    if (isset($_POST['supprimer_logement'])) {
        $id = intval($_POST['supprimer_logement']);
        $stmt = $bdd->prepare("DELETE FROM logements WHERE id = ?");
        $stmt->execute([$id]);
        header('Location: admin.php?view=logements');
        exit();
    }
}

$view = $_GET['view'] ?? 'utilisateurs';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - OmnesBnB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="menu.css">
    <style>
        .admin-nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
        }
        .admin-nav a {
            text-decoration: none;
            padding: 8px 16px;
            background-color: #eee;
            border-radius: 6px;
            color: #333;
            font-weight: bold;
        }
        .admin-nav a:hover {
            background-color: #ccc;
        }
        table {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            border-collapse: collapse;
            background: white;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        form {
            margin: 0;
        }
        button {
            padding: 6px 10px;
            border: none;
            background-color: #e74c3c;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<header class="header-messages">
    <a href="index.php" class="btn-retour">‹</a>
    <h2>Panneau d'administration</h2>
</header>

<nav class="admin-nav">
    <a href="admin.php?view=utilisateurs">Utilisateurs</a>
    <a href="admin.php?view=logements">Logements</a>
</nav>

<main>
    <?php if ($view === 'utilisateurs'): ?>
        <h3 style="text-align: center;">Liste des utilisateurs</h3>
        <table>
            <thead>
            <tr>
                <th>Nom complet</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $utilisateurs = $bdd->query("SELECT id, nom, prenom FROM utilisateursTest ORDER BY id DESC");
            foreach ($utilisateurs as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                    <td>
                        <?php if (!in_array($u['id'], $admins)): ?>
                            <form method="post">
                                <input type="hidden" name="supprimer_utilisateur" value="<?= $u['id'] ?>">
                                <button type="submit">🗑️</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($view === 'logements'): ?>
        <h3 style="text-align: center;">Liste des logements</h3>
        <table>
            <thead>
            <tr>
                <th>Titre du logement</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $logements = $bdd->query("SELECT id, titre FROM logements ORDER BY id DESC");
            foreach ($logements as $l): ?>
                <tr>
                    <td><?= htmlspecialchars($l['titre']) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="supprimer_logement" value="<?= $l['id'] ?>">
                            <button type="submit">🗑️</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p style="text-align: center;">Vue inconnue.</p>
    <?php endif; ?>
</main>
</body>
</html>
