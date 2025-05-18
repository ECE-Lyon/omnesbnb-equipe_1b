<?php
session_start();
require_once 'base_donnee.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'error' => 'non_connecte']);
    exit();
}

$id = intval($_POST['id'] ?? 0);
$email = trim($_POST['email'] ?? '');

$stmt = $bdd->prepare("SELECT id FROM utilisateursTest WHERE id = ? AND email = ?");
$stmt->execute([$id, $email]);

if ($stmt->fetch()) {
    echo json_encode(['success' => true, 'redirect' => 'messages.php?id=' . $id]);
} else {
    echo json_encode(['success' => false, 'error' => 'email_incorrect']);
}
