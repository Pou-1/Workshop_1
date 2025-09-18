<?php
require_once "connexion.php";

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

echo "<h2>Recommandations pour l’utilisateur $userId</h2>";
if (empty($recommendations)) {
    echo "<p>Aucune recommandation trouvée.</p>";
} else {
    echo "<ul>";
    foreach ($recommendations as $articleId => $score) {
        echo "<li>Article $articleId : " . number_format($score, 4) . "</li>";
    }
    echo "</ul>";
}

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
