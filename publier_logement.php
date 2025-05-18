<?php
session_start();

// Vérification de la session utilisateur (doit être connecté)
if (!isset($_SESSION['utilisateur']['id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['utilisateur']['id'];

// Connexion BDD
require_once 'base_donnee.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $pays = htmlspecialchars($_POST['pays']);
    $ville = htmlspecialchars($_POST['ville']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $places = intval($_POST['places']);
    $surfaces = floatval($_POST['surfaces']);
    $prix = floatval($_POST['prix']);
    $type = $_POST['type_location'];
    $statut = $_POST['statut'];

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
                $stmt = $bdd->prepare("INSERT INTO logements (id_utilisateur, titre, description, pays, ville, adresse, places, surfaces, prix_par_personne, type_location, statut, photo_principale) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $user_id,
                    $titre,
                    $description,
                    $pays,
                    $ville,
                    $adresse,
                    $places,
                    $surfaces,
                    $prix,
                    $type,
                    $statut,
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
        <script src="javascript/popup_publier.js" defer></script>
    </head>
    <body>

        <header>
            <section>
                <div class="header-container">
                    <div class="langue-accordeon">
                        <button id="selectlangue" class="select-langue">
                            <img src="images/fr.png" alt="fr" class="langue-icone">
                        </button>
                    </div>
                    <button onclick="window.location.href='index.php'" class="header-button logo-button">
                        <img src="images/logo_omnesBNB_noir.png" alt="Logo OmnesBNB" class="logo-img">
                        <h1>OmnesBnB</h1>
                    </button>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Bouton Profil visible après connexion -->
                        <button onclick="window.location.href='compte.php'" class="header-button" title="Voir mon profil">👤</button>
                    <?php else: ?>
                        <!-- Bouton + pour se connecter -->
                        <button onclick="window.location.href='login.php'" class="header-button" title="Se connecter">+</button>
                    <?php endif; ?>
                </div>
            </section>

        </header>

        <nav>
            <div class="choix-menu">
                <button onclick="window.location.href='../location.php'" class="button-choix">
                    <img src="images/icon_reservation.png" alt="logo réservation" class="logo-choix-reservation">
                    <p>Réservation</p>
                </button>
                <button onclick="window.location.href='recherche_logement.php'" class="button-choix">
                    <img src="images/icon_recherche.png" alt="logo recherche" class="logo-choix-recherche">
                    <p>Rechercher</p>
                </button>
                <button onclick="window.location.href='index.php'" class="button-choix">
                    <img src="images/logo_omnesBNB_blanc.png" alt="Logo OmnesBnB" class="logo-choix-omnes">
                    <p>Accueil</p>
                </button>
                <button onclick="window.location.href='publier_logement.php'" class="button-choix">
                    <img src="images/icon_publier.png" alt="logo publier" class="logo-choix-publier">
                    <p>Publier</p>
                </button>
                <button onclick="window.location.href='messagerie.php'" class="button-choix">
                    <img src="images/icon_message.png" alt="logo message" class="logo-choix-messagerie">
                    <p>Message</p>
                </button>
            </div>
        </nav>

        <h2>Publier un logement</h2>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titre" placeholder="Titre du logement" required>
            <textarea name="description" placeholder="Description détaillée" required></textarea>
            <input type="text" name="pays" placeholder="Nom du pays" required>
            <input type="text" name="ville" placeholder="Nom de la ville" required>
            <input type="text" name="adresse" placeholder="Adresse complète" required>
            <input type="number" name="places" min="1" placeholder="Nombre de places" required>
            <input type="number" name="prix" min="0" step="5" placeholder="Prix par personne (€)" required>
            <input type="number" name="surfaces" step="0.5" placeholder="Surfaces (en m^2)" required>
            <select name="type_location" required>
                <option value="logement entier">Logement entier</option>
                <option value="colocation">Colocation</option>
            </select>
            <select name="statut" required>
                <option value="disponible">disponible</option>
                <option value="réserver">réserver</option>
                <option value="indisponible">indisponible</option>
            </select>
            <input type="file" name="photo" accept="image/*" required>
            <button class="publier" type="submit">Publier</button>
        </form>

        <?php if ($message): ?>
            <script>
                const message = <?= json_encode($message); ?>;
                localStorage.setItem('popupMessage', message);
            </script>
        <?php endif; ?>

    </body>
</html>
