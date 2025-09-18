<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "connexion.php";
header("Content-Type: application/json; charset=UTF-8");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input["user_id"], $input["article_id"])) {
    echo json_encode(["success" => false, "message" => "Paramètres manquants"]);
    exit;
}

$user_id = (int)$input["user_id"];
$article_id = (int)$input["article_id"];

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM lus WHERE user_id = ? AND articles_id = ?");
    $stmt->execute([$user_id, $article_id]);
    $count = $stmt->fetchColumn();

    echo json_encode([
        "success" => true,
        "read" => $count > 0
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur SQL : " . $e->getMessage()
    ]);
}
