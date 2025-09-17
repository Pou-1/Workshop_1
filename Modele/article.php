<?php
// article.php → renvoie un tableau d’articles

// Préparer et exécuter la requête
$stmt = $pdo->query("SELECT id, titre, resumee, tags FROM articles");

// Stocker tous les articles dans un tableau associatif
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retourner le tableau
return $articles;
