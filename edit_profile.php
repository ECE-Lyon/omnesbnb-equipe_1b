<?php
session_start();
require_once 'base_donnee.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

// Récupérer les infos actuelles de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM utilisateursTest WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur introuvable.");
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $telephone = $_POST['telephone'];
    $photo_path = $user['photo_profil']; // par défaut on garde l'existante

// Si un fichier a été uploadé
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // crée le dossier si inexistant
        }

        $tmp_name = $_FILES['photo_profil']['tmp_name'];
        $file_name = "profil_" . uniqid() . "." . pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION);
        $destination = $upload_dir . $file_name;

        move_uploaded_file($tmp_name, $destination);
        $photo_path = $destination;
    }

    if (isset($_POST['supprimer_photo']) && $_POST['supprimer_photo'] == '1') {
        if (!empty($user['photo_profil']) && file_exists($user['photo_profil'])) {
            unlink($user['photo_profil']);
        }
        $photo_path = null;
    }



    // Construction de la requête
    if (!empty($new_password)) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE utilisateursTest SET prenom = ?, nom = ?, email = ?, mot_de_passe = ?, photo_profil = ?,telephone = ? WHERE id = ?";
        $params = [$prenom, $nom, $email, $hash,$photo_path, $telephone,$user_id];
    } else {
        $sql = "UPDATE utilisateursTest SET prenom = ?, nom = ?, email = ?, photo_profil = ? ,telephone = ?WHERE id = ?";
        $params = [$prenom, $nom, $email,$photo_path,$telephone, $user_id];
    }

    $update = $bdd->prepare($sql);
    $update->execute($params);

    header("Location: compte.php?modif=ok");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier mon profil</title>
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
<div class="edit-profile-container">
    <h1>Modifier mon profil</h1>
    <form method="post"enctype="multipart/form-data">
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>

        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?php echo htmlspecialchars($user['telephone'] ?? ''); ?>" placeholder="Ex : 0601020304">


        <label>Photo de profil :</label>
        <input type="file" name="photo_profil" accept="image/*">

        <?php if (!empty($user['photo_profil'])): ?>
            <p>Photo actuelle :</p>
            <img src="<?php echo htmlspecialchars($user['photo_profil']); ?>" alt="Photo actuelle" style="width: 100px; border-radius: 50%;">
            <div>
                <input type="checkbox" name="supprimer_photo" value="1" id="supprimer_photo">
                <label for="supprimer_photo">Supprimer la photo de profil actuelle</label>
            </div>
        <?php endif; ?>

        <label>Nouveau mot de passe :</label>
        <input type="password" name="new_password" placeholder="Laisser vide pour ne pas changer">

        <button type="submit">Enregistrer les modifications</button>
    </form>
    <a href="compte.php" class="retour">← Retour à mon profil</a>
</div>
</body>
</html>

