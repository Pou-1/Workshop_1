<?php
require_once "connexion.php";

// On suppose que l'id de l'utilisateur courant en session est récupéré ici
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 9; 

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

if (!isset($matrix[$userId])) {
    return [];
}

// 3. Calculer la similarité entre l'utilisateur courant et les autres
$similarities = [];
foreach ($matrix as $otherUserId => $scores) {
    if ($otherUserId == $userId) continue;
    $sim = pearsonCorrelation($matrix[$userId], $scores);
    echo "Similarité entre $userId et $otherUserId : $sim<br>"; // tu peux commenter cette ligne
    if ($sim > 0) {
        $similarities[$otherUserId] = $sim;
    }
}

// 4. Calculer le score de recommandation pour chaque article non vu/liké par l'utilisateur courant
$recommendations = [];
foreach ($matrix[$userId] as $articleId => $myScore) {
    if ($myScore > 0) continue; // On ne recommande pas les articles déjà vus/likés

    $weightedSum = 0;
    $simSum = 0;
    foreach ($similarities as $otherUserId => $sim) {
        $otherScore = $matrix[$otherUserId][$articleId] ?? 0;
        if ($otherScore > 0) {
            $weightedSum += $sim * $otherScore;
            $simSum += $sim;
        }
    }
    if ($simSum > 0) {
        $scoreReco = $weightedSum / $simSum;
        $recommendations[$articleId] = $scoreReco;
    }
}

// 5. Charger les infos des articles recommandés, triés par score décroissant
if (!empty($recommendations)) {
    arsort($recommendations); // Tri décroissant par score

    $articleIds = array_keys($recommendations);
    $in = str_repeat('?,', count($articleIds) - 1) . '?';
    $sql = "SELECT * FROM articles WHERE id IN ($in)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($articleIds);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ajouter le score de reco à chaque article
    foreach ($articles as &$article) {
        $article['score_reco'] = $recommendations[$article['id']];
    }

    // Trier les articles selon le score de reco
    usort($articles, function($a, $b) {
        return $b['score_reco'] <=> $a['score_reco'];
    });

    return $articles;
} else {
    return [];
}

