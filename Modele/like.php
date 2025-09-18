<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "connexion.php";

header("Content-Type: application/json; charset=UTF-8");

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input["user_id"], $input["article_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Paramètres manquants"
    ]);
    exit;
}

$user_id = (int)$input["user_id"];
$article_id = (int)$input["article_id"];

try {
    $stmt = $pdo->prepare("INSERT INTO likes (user_id, articles_id, date_like) VALUES (?, ?, NOW())");
    $stmt->execute([$user_id, $article_id]);

    echo json_encode([
        "success" => true,
        "message" => "Like enregistré"
    ]);
} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        echo json_encode([
            "success" => false,
            "message" => "Cet article est déjà liké par cet utilisateur"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Erreur SQL : " . $e->getMessage()
        ]);
    }
}
