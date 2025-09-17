<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../Modele/connexion.php";

// Charger les articles
$articles = require_once "../Modele/article.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Workshop</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <header class="bg-blue-600 text-white p-6 shadow-md">
    <h1 class="text-2xl font-bold">Bienvenue sur Workshop</h1>
  </header>

  <main class="container mx-auto mt-8 p-4">
    <h2 class="text-xl font-semibold mb-4">Liste des articles</h2>
    <ul class="space-y-4">
      <?php foreach ($articles as $article): ?>
        <li class="bg-white p-4 shadow rounded-lg hover:border-blue border-2 border-white">
          <h3 class="text-lg font-bold"><?= htmlspecialchars($article['titre']) ?></h3>
          <p class="text-gray-700"><?= htmlspecialchars($article['contenu']) ?></p>
          <span class="text-sm text-blue-500">#<?= htmlspecialchars($article['tags']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>

  <footer class="bg-gray-200 text-center p-4 mt-8">
    <p>&copy; <?= date("Y") ?> - Workshop</p>
  </footer>

</body>
</html>
