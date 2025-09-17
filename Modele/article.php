<?php
// article.php → renvoie un tableau d’articles avec tags et auteurs détaillés

// Charger les articles
$stmt = $pdo->query("SELECT id, titre, resumee, tags, date_publication, auteurs FROM articles");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($articles as &$article) {
    // --- Tags ---
    $tagIds = array_filter(array_map('trim', explode(',', $article['tags'])));
    $tagDetails = [];
    if (!empty($tagIds)) {
        $in  = str_repeat('?,', count($tagIds) - 1) . '?';
        $stmtTags = $pdo->prepare("SELECT id, nom FROM tags WHERE id IN ($in)");
        $stmtTags->execute($tagIds);
        $tagDetails = $stmtTags->fetchAll(PDO::FETCH_ASSOC);
    }
    $article['tags'] = $tagDetails;

    // --- Auteurs ---
    $authorIds = array_filter(array_map('trim', explode(',', $article['auteurs'])));
    $authorDetails = [];
    if (!empty($authorIds)) {
        $in  = str_repeat('?,', count($authorIds) - 1) . '?';
        $stmtAuthors = $pdo->prepare("SELECT id, nom, prenom FROM auteurs WHERE id IN ($in)");
        $stmtAuthors->execute($authorIds);
        $authorDetails = $stmtAuthors->fetchAll(PDO::FETCH_ASSOC);
    }
    $article['auteurs'] = $authorDetails;
}

return $articles;
