<?php
require_once "connexion.php";

// Charger tous les articles
$sql = "SELECT id, titre, resumee, tags, date_publication, auteurs FROM articles";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$filteredArticles = [];
$searchTerm = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : null;
$selectedTag = isset($_GET['tag']) ? strtolower(trim($_GET['tag'])) : null;

foreach ($articles as &$article) {
    $tagIds = array_filter(array_map('trim', explode(',', $article['tags'])));
    $tagDetails = [];
    if (!empty($tagIds)) {
        $in  = str_repeat('?,', count($tagIds) - 1) . '?';
        $stmtTags = $pdo->prepare("SELECT id, nom FROM tags WHERE id IN ($in)");
        $stmtTags->execute($tagIds);
        $tagDetails = $stmtTags->fetchAll(PDO::FETCH_ASSOC);
    }
    $article['tags'] = $tagDetails;

    $authorIds = array_filter(array_map('trim', explode(',', $article['auteurs'])));
    $authorDetails = [];
    if (!empty($authorIds)) {
        $in  = str_repeat('?,', count($authorIds) - 1) . '?';
        $stmtAuthors = $pdo->prepare("SELECT id, nom, prenom FROM auteurs WHERE id IN ($in)");
        $stmtAuthors->execute($authorIds);
        $authorDetails = $stmtAuthors->fetchAll(PDO::FETCH_ASSOC);
    }
    $article['auteurs'] = $authorDetails;

    // --- Filtrage ---
    $matchesSearch = false;
    $matchesTag = false;

    // --- Recherche par titre ou auteur ---
    if ($searchTerm) {
        $foundInTitle = strpos(strtolower($article['titre']), $searchTerm) !== false;
        $foundInAuthor = false;
        foreach ($authorDetails as $auteur) {
            $fullName = strtolower($auteur['prenom'] . ' ' . $auteur['nom']);
            if (
                strpos($fullName, $searchTerm) !== false ||
                strpos(strtolower($auteur['prenom']), $searchTerm) !== false ||
                strpos(strtolower($auteur['nom']), $searchTerm) !== false
            ) {
                $foundInAuthor = true;
                break;
            }
        }
        $matchesSearch = $foundInTitle || $foundInAuthor;
    } else {
        $matchesSearch = true;
    }

    // --- Filtrage par tag (navbar) ---
    if ($selectedTag) {
        foreach ($tagDetails as $tag) {
            if (strtolower($tag['nom']) === $selectedTag) {
                $matchesTag = true;
                break;
            }
        }
    } else {
        $matchesTag = true;
    }

    if ($matchesSearch && $matchesTag) {
        $filteredArticles[] = $article;
    }
}

return $filteredArticles;
