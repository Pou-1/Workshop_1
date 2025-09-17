<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();

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
    <h1 class="text-2xl font-bold">
        Bienvenue
        <?php if (isset($_SESSION['user'])): ?>
            <?= htmlspecialchars($_SESSION['user']['nom']) . " " . htmlspecialchars($_SESSION['user']['prenom']) ?>
        <?php endif; ?>
    </h1>
</header>

<!-- Navbar avec tags défilants et barre de recherche -->
<nav class="bg-white shadow flex items-center justify-between px-10 py-4 mb-2">
    <!-- Tags avec flèches de défilement -->
    <div class="flex items-center gap-2">
        <!-- Flèche gauche -->
        <button id="leftArrow" class="text-xl px-2 text-gray-600 hover:text-black" disabled>
            &#8592;
        </button>

        <!-- Conteneur scrollable des tags -->
        <div id="tagContainer" class="flex gap-2 overflow-hidden w-[400px]">
            <?php
            $navbarTags = ['PHP', 'JS', 'CSS', 'SQL', 'VueJS', 'Laravel', 'React', 'Docker'];
            foreach ($navbarTags as $tag): ?>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded cursor-pointer whitespace-nowrap min-w-fit">
                    #<?= htmlspecialchars($tag) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Flèche droite -->
        <button id="rightArrow" class="text-xl px-2 text-gray-600 hover:text-black">
            &#8594;
        </button>
    </div>

    <!-- Barre de recherche -->
    <form method="GET" action="" class="flex items-center gap-2">
        <input
            type="text"
            name="search"
            placeholder="Rechercher un article..."
            class="border rounded px-3 py-1 focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">
            Rechercher
        </button>
    </form>
</nav>



  <main class="container mx-auto mt-8 p-4">
    <h2 class="text-xl font-semibold mb-4">Liste des articles</h2>
    <ul class="gap-4 grid grid-cols-3">
      <?php foreach ($articles as $article): ?>
    <li class="bg-white p-4 shadow rounded-lg hover:border-blue-500 transition hover:bg-blue-50 border-2 border-white">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-bold"><?= htmlspecialchars($article['titre']) ?></h3>
            <span class="text-sm text-gray-500"><?= htmlspecialchars($article['date_publication']) ?></span>
        </div>

        <p class="text-gray-700 mb-3"><?= htmlspecialchars($article['resumee']) ?></p>

        <!-- Tags -->
        <div class="flex flex-wrap gap-2 mb-2">
            <?php foreach ($article['tags'] as $tag): ?>
                <span class="px-2 py-1 text-sm bg-blue-100 text-blue-600 rounded">
                    #<?= htmlspecialchars($tag['nom']) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- Auteurs -->
        <div class="flex flex-wrap gap-2">
            <?php foreach ($article['auteurs'] as $auteur): ?>
                <span class="px-2 py-1 text-sm bg-green-100 text-green-600 rounded">
                    <?= htmlspecialchars($auteur['prenom'] . " " . $auteur['nom']) ?>
                </span>
            <?php endforeach; ?>
        </div>
    </li>
<?php endforeach; ?>

    </ul>
  </main>

  <footer class="bg-gray-200 text-center p-4 mt-8">
    <p>&copy; <?= date("Y") ?> - Workshop</p>
  </footer>

</body>
</html>

<script>
    const tagContainer = document.getElementById('tagContainer');
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');

    const scrollAmount = 120; // Ajuste selon la taille des tags

    leftArrow.addEventListener('click', () => {
        tagContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        updateArrows();
    });

    rightArrow.addEventListener('click', () => {
        tagContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        updateArrows();
    });

    function updateArrows() {
        setTimeout(() => {
            leftArrow.disabled = tagContainer.scrollLeft <= 0;
            rightArrow.disabled = tagContainer.scrollLeft + tagContainer.clientWidth >= tagContainer.scrollWidth;
        }, 200);
    }

    window.addEventListener('load', updateArrows);
    tagContainer.addEventListener('scroll', updateArrows);
</script>
