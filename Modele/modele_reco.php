<?php
require_once "connexion.php";

// On suppose que l'id de l'utilisateur courant en session est récupéré ici
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 2;

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

$matrix = [];
foreach ($rows as $row) {
    $matrix[$row['user_id']][$row['article_id']] = (float)$row['score'];
}

$similarities = [];
foreach ($matrix as $otherUserId => $scores) {
    if ($otherUserId == $userId) continue;
    $sim = pearsonCorrelation($matrix[$userId], $scores);
    if ($sim > 0) {
        $similarities[$otherUserId] = $sim;
    }
}

$scores = [];
$normalisation = [];

foreach ($similarities as $otherUserId => $sim) {
    foreach ($matrix[$otherUserId] as $articleId => $score) {
        if ($matrix[$userId][$articleId] == 0 && $score > 0) {
            if (!isset($scores[$articleId])) {
                $scores[$articleId] = 0;
                $normalisation[$articleId] = 0;
            }
            $scores[$articleId] += $score * $sim;
            $normalisation[$articleId] += $sim;
        }
    }
}

$recommendations = [];
foreach ($scores as $articleId => $totalScore) {
    $recommendations[$articleId] = $totalScore / $normalisation[$articleId];
}

arsort($recommendations);

function pearsonCorrelation($scores1, $scores2) {
    $common = [];
    foreach ($scores1 as $articleId => $s1) {
        if (isset($scores2[$articleId]) && ($s1 > 0 || $scores2[$articleId] > 0)) {
            $common[$articleId] = true;
        }
    }

    $n = count($common);
    if ($n == 0) return 0;

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

    $num = $pSum - (($sum1 * $sum2) / $n);
    $den = sqrt(($sum1Sq - pow($sum1, 2) / $n) * ($sum2Sq - pow($sum2, 2) / $n));
    if ($den == 0) return 0;

    return $num / $den;
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

