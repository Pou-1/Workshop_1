<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

require_once "../Modele/connexion.php";
require_once "../Modele/articleLiked.php";

$userId = $_SESSION['user']['id'] ?? null;

if ($userId) {
    $articlesLiked = getLikedArticles($pdo, $userId);
} else {
    $articlesLiked = [];
}

$articles = include "../Modele/article.php";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <script src="../css/style.css"></script>
  <script src="../js/navbar.js" defer></script>
    <title>Accueil - Workshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/vue_main.js"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-gray-100 text-gray-800 w-screen overflow-x-hidden">
    
<div class="w-full shadow bg-red-600 flex justify-center shadow-md">
<header class="w-2/3 text-white gap-2 py-6 flex-between flex">
    <h1 class="text-2xl w-full">
        Bienvenue
        <span class="font-bold text-2xl">
            <?php if (isset($_SESSION['user'])): ?>
                <?= htmlspecialchars($_SESSION['user']['nom']) . " " . htmlspecialchars($_SESSION['user']['prenom']) ?>
            <?php endif; ?>
        </span>
    </h1>
    <img src="../logo/like.svg" onclick="FilterLikeArticles()" alt="Logout" id="FilterLike" class="w-10 cursor-pointer rounded-full p-2 bg-[#2c3131] text-red-500 h-10 hover:opacity-80">
</header>
</div>

<div class="w-full shadow bg-white flex justify-center">
<nav class="w-2/3 flex items-center justify-between py-4 mb-2">
    <div class="flex items-center gap-2">
        <button id="leftArrow" class="text-xl px-2 text-gray-600 hover:text-black" disabled>
            &#8592;
        </button>

        <div id="tagContainer" class="flex gap-2 overflow-hidden w-[400px]">
            <?php
            $navbarTags = ['Découverte', 'Science', 'Environnement', 'Technologie', 'Espace', 'Sport', 'Santé', 'Culture', 'Histoire', 'Art', 'Musique', 'Cinéma', 'Littérature'];
            foreach ($navbarTags as $tag):
                $isSelected = (isset($_GET['tag']) && strtolower($_GET['tag']) === strtolower($tag));
                // Si le tag est sélectionné, le lien retire le filtre (redirige vers la page sans paramètre tag)
                $href = $isSelected ? '?' : '?tag=' . urlencode($tag);
            ?>
                <a href="<?= $href ?>"
                    class="px-3 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200 whitespace-nowrap min-w-fit <?= $isSelected ? 'bg-blue-600 text-white' : '' ?>">
                    #<?= htmlspecialchars($tag) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <button id="rightArrow" class="text-xl px-2 text-gray-600 hover:text-black">
            &#8594;
        </button>
    </div>

    <form method="GET" action="" class="flex items-center gap-2">
        <input
        type="text"
        name="search"
        placeholder="Rechercher un article..."
        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
        class="border rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
    />

        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
            Rechercher
        </button>
    </form>
</nav>
    </div>
 
<div class="w-full flex justify-center relative">
    <main class="container w-2/3 absolute mt-8 p-4 h-full trans-fast" id="articlesDiv">
        <h2 class="text-xl font-semibold mb-4">Liste des articles</h2>
        <ul class="gap-4 grid grid-cols-3">
            <?php foreach ($articles as $article): ?>
                <li 
                    class="cursor-pointer bg-white p-4 shadow rounded-lg hover:border-red-500 transition hover:bg-red-50 border-2 border-white"
                    onclick="openArticleModal(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($_SESSION['user']['id'])?>)">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold truncate w-3/4"><?= htmlspecialchars($article['titre']) ?></h3>
                        <span class="text-sm text-gray-500"><?= htmlspecialchars($article['date_publication']) ?></span>
                    </div>
                    <p class="text-gray-700 mb-3"><?= htmlspecialchars($article['resumee']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>

    <main class="container w-2/3 mt-8 p-4 absolute h-full opacity-0 translate-x-[1000px] trans-fast" id="likedArticlesDiv">
        <h2 class="text-xl font-semibold mb-4">Liste des articles Likés</h2>
        <ul class="gap-4 grid grid-cols-3">
            <?php foreach ($articlesLiked as $article): ?>
                <li 
                    class="cursor-pointer bg-white p-4 shadow rounded-lg hover:border-red-500 transition hover:bg-red-50 border-2 border-white"
                    onclick="openArticleModal(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($_SESSION['user']['id'])?>)">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-bold"><?= htmlspecialchars($article['titre']) ?></h3>
                        <span class="text-sm text-gray-500"><?= htmlspecialchars($article['date_publication']) ?></span>
                    </div>
                    <p class="text-gray-700 mb-3"><?= htmlspecialchars($article['resumee']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
</div>
  <div id="articleModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
    <div class="absolute w-full h-full z-0" onclick="closeArticleModal()"></div>
    <div class="bg-white rounded-xl shadow-lg z-10 max-w-3xl w-full p-10 relative">
      
      <button id="LikeButton" onclick="LikedArticleModal(<?= htmlspecialchars($_SESSION['user']['id'])?>, currentArticleId)"  class="absolute right-10 top-10 border-red-600 border bg-red-100 text-red-600 px-4 py-2 rounded hover:bg-red-200">
        ❤️ Like !
      </button>

      <h2 id="modalTitle" class="text-2xl font-bold mb-2"></h2>
      <p id="modalDate" class="text-sm text-gray-500 mb-4"></p>
      <img id="modalImage" class="w-full h-64 object-cover rounded mb-4 hidden" />

      <p id="modalResumee" class="text-gray-700 mb-4"></p>
      <p id="modalContenu" class="text-gray-800 mb-4"></p>

      <div class="flex flex-wrap gap-2 mb-2" id="modalTags"></div>
      <div class="flex flex-wrap gap-2" id="modalAuteurs"></div>
    </div>
  </div>

  <footer class="absolute w-full px-52 bottom-0 ">
    <div class="bg-[#2c3131] rounded-t-xl w-full text-white text-center p-4 mt-8">
        <p>&copy; <?= date("Y") ?> - Workshop</p>
    </div>
  </footer>

</body>
</html>
