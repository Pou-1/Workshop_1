<?php
$data = require "../Modele/modele_reco.php";
$matrix = $data["matrix"];
$similarity = $data["similarity"];
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 2;

$scores = [];
$normalisation = [];

foreach ($similarity as $otherUserId => $sim) {
    if ($sim <= 0) continue; // ignorer corrélations négatives
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
?>

<h2>Matrice Utilisateurs ↔ Articles (scores)</h2>
<pre><?php print_r($matrix); ?></pre>

<h2>Similarités avec l’utilisateur <?= $userId ?></h2>
<ul>
    <?php foreach ($similarity as $otherUserId => $sim): ?>
        <li>User <?= $userId ?> ↔ User <?= $otherUserId ?> :
            <?= number_format($sim, 4) ?>
        </li>
    <?php endforeach; ?>
</ul>

<h2>Articles recommandés pour vous</h2>
<?php if (!empty($recommendations)): ?>
    <ol>
        <?php foreach ($recommendations as $articleId => $score): ?>
            <li>
                Article ID <?= $articleId ?> — Score :
                <?= number_format($score, 4) ?>
            </li>
        <?php endforeach; ?>
    </ol>
<?php else: ?>
    <p>Aucune recommandation trouvée.</p>
<?php endif; ?>
