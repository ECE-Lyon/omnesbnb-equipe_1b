<?php
session_start();
require 'base_donnee.php'; // votre connexion PDO

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$id_utilisateur = $_SESSION['id'];
$id_logement = isset($_POST['id_logement']) ? intval($_POST['id_logement']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if (!$id_logement || !in_array($action, ['add', 'remove'])) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Paramètres invalides']);
    exit;
}

// Vérifier d'abord si le favori existe déjà
$stmt = $bdd->prepare("SELECT COUNT(*) FROM favoris WHERE id_utilisateur = ? AND id_logement = ?");
$stmt->execute([$id_utilisateur, $id_logement]);
$existe = $stmt->fetchColumn() > 0;

$response = ['status' => 'success'];

if ($action === 'add') {
    // Si le favori existe déjà, pas besoin de l'ajouter
    if (!$existe) {
        $stmt = $bdd->prepare("INSERT INTO favoris (id_utilisateur, id_logement) VALUES (?, ?)");
        $stmt->execute([$id_utilisateur, $id_logement]);
        $response['message'] = 'Ajouté aux favoris';
    } else {
        $response['message'] = 'Déjà dans les favoris';
    }
} elseif ($action === 'remove') {
    if ($existe) {
        $stmt = $bdd->prepare("DELETE FROM favoris WHERE id_utilisateur = ? AND id_logement = ?");
        $stmt->execute([$id_utilisateur, $id_logement]);
        $response['message'] = 'Retiré des favoris';
    } else {
        $response['message'] = 'Favori inexistant';
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>

