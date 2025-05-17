<?php
// traitement_recherche.php - Fichier qui gère les requêtes AJAX pour la recherche de logements

// Démarrer la session
session_start();

// Inclure le fichier de connexion à la base de données
require_once 'base_donnee.php';

// Vérifier si la requête est de type POST et contient l'action 'rechercher'
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rechercher') {
    // Récupérer et sécuriser les paramètres de recherche
    $destination = isset($_POST['destination']) ? $_POST['destination'] : '';
    $dateDebut = isset($_POST['dateDebut']) ? $_POST['dateDebut'] : '';
    $dateFin = isset($_POST['dateFin']) ? $_POST['dateFin'] : '';
    $voyageurs = isset($_POST['voyageurs']) ? intval($_POST['voyageurs']) : 1;
    $typeLogement = isset($_POST['typeLogement']) ? $_POST['typeLogement'] : '';
    $prixMax = isset($_POST['prixMax']) ? floatval($_POST['prixMax']) : 1000;
    $surfaceMin = isset($_POST['surfaceMin']) ? intval($_POST['surfaceMin']) : 0;

    // Convertir l'ID du type de logement en valeur pour la base de données
    $typeLogementValeur = '';
    if ($typeLogement === 'logement-entier') {
        $typeLogementValeur = 'logement entier';
    } elseif ($typeLogement === 'colocation') {
        $typeLogementValeur = 'colocation';
    }

    // Construire la requête SQL
    $sql = "SELECT * FROM logements WHERE statut = 'disponible'";
    $params = [];

    // Filtrer par destination (recherche dans pays, ville et adresse)
    if (!empty($destination)) {
        /*
        $sql .= " AND (pays LIKE ? OR ville LIKE ? OR adresse LIKE ?)";
        $searchTerm = "%{$destination}%";
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        */
        $sql .= " AND (LOWER(ville) LIKE ?)";
        $params[] = '%' . strtolower(trim($destination)) . '%';
    }

    // Filtrer par dates
    /*
    if (!empty($dateDebut)) {
        $sql .= " AND date_debut >= ?";
        $params[] = $dateDebut;
    }

    if (!empty($dateFin)) {
        $sql .= " AND date_fin <= ?";
        $params[] = $dateFin;
    }

    // Filtrer par nombre de voyageurs
    if ($voyageurs > 1) {
        $sql .= " AND places >= ?";
        $params[] = $voyageurs;
    }

    // Filtrer par type de logement
    if (!empty($typeLogementValeur)) {
        $sql .= " AND type_location = ?";
        $params[] = $typeLogementValeur;
    }

    // Filtrer par prix maximum
    if ($prixMax > 0) {
        $sql .= " AND prix_par_personne <= ?";
        $params[] = $prixMax;
    }

    // Filtrer par surface minimum
    if ($surfaceMin > 0) {
        $sql .= " AND surfaces >= ?";
        $params[] = $surfaceMin;
    }
    */

    // Trier les résultats (par exemple par date de création, du plus récent au plus ancien)
    $sql .= " ORDER BY date_creation DESC";

    try {
        // Préparer et exécuter la requête
        $stmt = $bdd->prepare($sql);
        $stmt->execute($params);

        // Récupérer tous les résultats
        $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les résultats au format JSON
        header('Content-Type: application/json');
        echo json_encode($resultats);
        exit;

    } catch (PDOException $e) {
        // En cas d'erreur, retourner un message d'erreur
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la recherche: ' . $e->getMessage()]);
        exit;
    }
} else {
    // Si la requête n'est pas valide, retourner une erreur
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Requête invalide']);
    exit;
}
