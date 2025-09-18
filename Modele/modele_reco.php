<?php
require_once "connexion.php";

// On suppose que l'id de l'utilisateur courant en session est récupéré ici
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1; 

// ======================================================================
// 1. Construire la matrice utilisateur ↔ article avec des scores
// ======================================================================
// Rappel des règles :
//  - 0 = l'utilisateur n'a jamais consulté ni liké l'article
//  - 1 = l'utilisateur a consulté mais pas liké
//  - 2 = l'utilisateur a consulté ET liké
// ======================================================================
$sql = "
    SELECT 
        u.id AS user_id,
        a.id AS article_id,
        CASE
            WHEN l.id IS NOT NULL AND k.id IS NOT NULL THEN 2
            WHEN l.id IS NOT NULL AND k.id IS NULL THEN 1
            ELSE 0
        END AS score
    FROM users u
    CROSS JOIN articles a
    LEFT JOIN lus l ON l.user_id = u.id AND l.articles_id = a.id
    LEFT JOIN likes k ON k.user_id = u.id AND k.articles_id = a.id
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// On construit un tableau associatif :
// $matrix[user_id][article_id] = score
$matrix = [];
foreach ($rows as $row) {
    $matrix[$row['user_id']][$row['article_id']] = (int)$row['score'];
}

// ======================================================================
// 2. Fonction pour calculer la similarité entre deux utilisateurs
//    via le coefficient de corrélation de Pearson
// ======================================================================
function pearsonCorrelation($scores1, $scores2) {
    // On ne garde que les articles que les deux utilisateurs ont vus/likés
    $common = [];
    foreach ($scores1 as $articleId => $s1) {
        if (isset($scores2[$articleId]) && ($s1 > 0 || $scores2[$articleId] > 0)) {
            $common[$articleId] = true;
        }
    }

    $n = count($common);
    if ($n == 0) return 0; // aucun article en commun → similarité nulle

    // Calcul des sommes et produits pour Pearson
    $sum1 = $sum2 = $sum1Sq = $sum2Sq = $pSum = 0;
    foreach ($common as $articleId => $_) {
        $s1 = $scores1[$articleId];
        $s2 = $scores2[$articleId];
        $sum1 += $s1;
        $sum2 += $s2;
        $sum1Sq += pow($s1, 2);
        $sum2Sq += pow($s2, 2);
        $pSum += $s1 * $s2;
    }

    // Numérateur = covariance
    $num = $pSum - (($sum1 * $sum2) / $n);
    // Dénominateur = produit des écarts-types
    $den = sqrt(($sum1Sq - pow($sum1, 2) / $n) * ($sum2Sq - pow($sum2, 2) / $n));
    if ($den == 0) return 0;

    return $num / $den; // valeur entre -1 et 1
}
