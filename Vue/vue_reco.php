<h2>Articles recommandés pour vous</h2>

<?php if (!empty($recommendedArticles)): ?>
    <ul>
        <?php foreach ($recommendedArticles as $article): ?>
            <li>
                <img src="<?= htmlspecialchars($article['img_principale']) ?>" 
                     alt="<?= htmlspecialchars($article['titre']) ?>" width="100">
                <strong><?= htmlspecialchars($article['titre']) ?></strong>
                <p><?= htmlspecialchars($article['resumee']) ?></p>
                <small>Score de recommandation : <?= round($article['score_reco'], 2) ?></small>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucune recommandation disponible pour le moment.</p>
<?php endif; ?>
