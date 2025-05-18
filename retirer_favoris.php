<?php
session_start();
require_once 'base_donnee.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Non autorisé']);
    exit;
}

$id_utilisateur = $_SESSION['id'];
$id_logement = isset($_POST['id_logement']) ? intval($_POST['id_logement']) : 0;

if (!$id_logement) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID de logement manquant']);
    exit;
}

// Vérifier si le favori existe avant de le supprimer
$stmt = $bdd->prepare("SELECT COUNT(*) FROM favoris WHERE id_utilisateur = ? AND id_logement = ?");
$stmt->execute([$id_utilisateur, $id_logement]);
$existe = $stmt->fetchColumn() > 0;

$response = ['status' => 'success'];

if ($existe) {
    $stmt = $bdd->prepare("DELETE FROM favoris WHERE id_utilisateur = ? AND id_logement = ?");
    $stmt->execute([$id_utilisateur, $id_logement]);
    $response['message'] = 'Favori supprimé avec succès';
} else {
    $response['message'] = 'Ce favori n\'existe pas';
}

header('Content-Type: application/json');
echo json_encode($response);
?>