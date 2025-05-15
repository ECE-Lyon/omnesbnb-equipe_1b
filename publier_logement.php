<?php
session_start();

// Vérification de la session utilisateur (doit être connecté)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Connexion BDD
require_once 'base_donnee.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $places = intval($_POST['places']);
    $prix = floatval($_POST['prix']);
    $type = $_POST['type_location'];

    // Gestion upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photo = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2 Mo

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $photo['tmp_name']);
        finfo_close($finfo);

        if (in_array($mimeType, $allowedTypes) && $photo['size'] <= $maxSize) {
            $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);
            $unique_name = uniqid('logement_', true) . '.' . $extension;
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
            $target_file = $target_dir . $unique_name;

            if (move_uploaded_file($photo['tmp_name'], $target_file)) {
                // Insertion en BDD
                $stmt = $bdd->prepare("INSERT INTO logements (id_utilisateur, titre, description, adresse, date_debut, date_fin, places, prix_par_personne, type_location, photo_principale) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $_SESSION['user_id'],
                    $titre,
                    $description,
                    $adresse,
                    $date_debut,
                    $date_fin,
                    $places,
                    $prix,
                    $type,
                    $target_file
                ]);

                $message = "✅ Logement publié avec succès !";
            } else {
                $message = "❌ Erreur lors du transfert de la photo.";
            }
        } else {
            $message = "❌ Fichier invalide ou trop volumineux (Max 2 Mo, JPG/PNG).";
        }
    } else {
        $message = "❌ Veuillez sélectionner une photo.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Publier un logement</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="publier_logement.css">
    </head>
    <body>

        <h2 style="text-align:center;">Publier un logement</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre du logement" required>
            <textarea name="description" placeholder="Description détaillée" required></textarea>
            <input type="text" name="adresse" placeholder="Adresse complète" required>
            <input type="date" name="date_debut" required>
            <input type="date" name="date_fin" required>
            <input type="number" name="places" min="1" placeholder="Nombre de places" required>
            <input type="number" name="prix" step="0.01" placeholder="Prix par personne (€)" required>
            <select name="type_location" required>
                <option value="logement entier">Logement entier</option>
                <option value="colocation">Colocation</option>
            </select>
            <input type="file" name="photo" accept="image/*" required>
            <button type="submit">Publier</button>
        </form>

        <?php if ($message) : ?>
        <div class="message"><?= $message ?></div>
        <?php endif; ?>

    </body>
</html>
