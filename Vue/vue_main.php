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
  <script src="../js/vue_main.js"></script>
  <script src="../css/style.css"></script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

  <!-- Header -->
  <header class="bg-red-600 text-white gap-2 p-6 pl-52 flex shadow-md">
      <h1 class="text-2xl font-bold">Bienvenue</h1>
      <p class="text-2xl bold">
        <?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?>
      </p>
  </header>

  <!-- Liste d'articles -->
  <main class="container mx-auto mt-8 p-4 h-full">
    <h2 class="text-xl font-semibold mb-4">Liste des articles</h2>
    <ul class="gap-4 grid grid-cols-3">
        <?php foreach ($articles as $article): ?>
            <li 
                class="cursor-pointer bg-white p-4 shadow rounded-lg hover:border-red-500 transition hover:bg-red-50 border-2 border-white"
                onclick="openArticleModal(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>)">
                
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-bold"><?= htmlspecialchars($article['titre']) ?></h3>
                    <span class="text-sm text-gray-500"><?= htmlspecialchars($article['date_publication']) ?></span>
                </div>
                <p class="text-gray-700 mb-3"><?= htmlspecialchars($article['resumee']) ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
  </main>

  <!-- Modal caché -->
  <div id="articleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="absolute w-full h-full z-0" onclick="closeArticleModal()"></div>
    <div class="bg-white rounded-lg shadow-lg z-10 max-w-3xl w-full p-6 relative">
      
      <!-- Bouton fermer -->
      <button onclick="LikedArticleModal()" class="absolute top-3 right-3 text-gray-600 hover:text-red-600 text-xl font-bold">
        Like !
      </button>

      <!-- Contenu dynamique -->
      <h2 id="modalTitle" class="text-2xl font-bold mb-2"></h2>
      <p id="modalDate" class="text-sm text-gray-500 mb-4"></p>
      <img id="modalImage" class="w-full h-64 object-cover rounded mb-4 hidden" />

      <p id="modalResumee" class="text-gray-700 mb-4"></p>
      <p id="modalContenu" class="text-gray-800 mb-4"></p>

      <div class="flex flex-wrap gap-2 mb-2" id="modalTags"></div>
      <div class="flex flex-wrap gap-2" id="modalAuteurs"></div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="absolute w-full px-52 bottom-0 ">
    <div class="bg-[#2c3131] rounded-t-xl w-full text-white text-center p-4 mt-8">
        <p>&copy; <?= date("Y") ?> - Workshop</p>
    </div>
  </footer>

</body>
</html>
