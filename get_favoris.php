<?php
session_start();
require_once 'base_donnee.php';

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header('Content-Type: application/json');
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$id_utilisateur = $_SESSION['id'];

// Récupérer la liste des IDs de logements en favoris
$stmt = $bdd->prepare("SELECT DISTINCT id_logement FROM favoris WHERE id_utilisateur = ?");
$stmt->execute([$id_utilisateur]);
$favoris = $stmt->fetchAll(PDO::FETCH_COLUMN);

header('Content-Type: application/json');
echo json_encode($favoris);
?>
