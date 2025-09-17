<?php
require_once 'connexion.php';

// Récupérer les données envoyées en JSON
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input["user_id"]) || !isset($input["article_id"])) {
    echo json_encode(["success" => false, "message" => "Paramètres manquants"]);
    exit;
}

$user_id = (int)$input["user_id"];
$article_id = (int)$input["article_id"];

function insertLike($user_id, $article_id) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, article_id, date_like) VALUES (?, ?, NOW())");
    return $stmt->execute([$user_id, $article_id]);
}

if (insertLike($user_id, $article_id)) {
    echo json_encode(["success" => true, "message" => "Like ajouté"]);
} else {
    echo json_encode(["success" => false, "message" => "Erreur lors de l'insertion"]);
}
