<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "../Modele/connexion.php";

$articles = require_once "../Modele/article.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Workshop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="../css/style.css"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <header class="bg-blue-600 text-white gap-2 p-6 pl-52 flex shadow-md">
        <h1 class="text-2xl font-bold">Bienvenue</h1>
        <p class="text-2xl">Surname</p>
        <p class="font-bold text-2xl">L !</p>
    </header>

  <main class="container mx-auto mt-8 p-4">
    <h2 class="text-xl font-semibold mb-4">Liste des articles</h2>
    <ul class="gap-4 grid grid-cols-3">
      <?php foreach ($articles as $article): ?>
        <li class="bg-white p-4 shadow rounded-lg hover:border-blue-500 trans-fast hover:bg-blue-50 border-2 border-white">
          <h3 class="text-lg font-bold"><?= htmlspecialchars($article['titre']) ?></h3>
          <p class="text-gray-700"><?= htmlspecialchars($article['resumee']) ?></p>
          <span class="text-sm text-blue-500">#<?= htmlspecialchars($article['tags']) ?></span>
          <span class="text-sm text-blue-500">#<?= htmlspecialchars($article['auteurs']) ?></span>
          <span class="text-sm text-blue-500">#<?= htmlspecialchars($article['date_publication']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </main>

  <footer class="bg-gray-200 text-center p-4 mt-8">
    <p>&copy; <?= date("Y") ?> - Workshop</p>
  </footer>

</body>
</html>
