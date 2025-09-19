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

$data = require "../Modele/modele_reco.php";
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
            <div onclick="FilterLikeArticles()" class="cursor-pointer items-center justify-center flex gap-2 px-3 py-1 rounded-full">
                <p class="text-white font-bold">Likes</p>
                <div  alt="Logout" id="FilterLike"
                    class="w-8 rounded-full p-2 text-red-500 fill-current bg-[#2c3131] h-8 hover:opacity-80">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free 7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M305 151.1L320 171.8L335 151.1C360 116.5 400.2 96 442.9 96C516.4 96 576 155.6 576 229.1L576 231.7C576 343.9 436.1 474.2 363.1 529.9C350.7 539.3 335.5 544 320 544C304.5 544 289.2 539.4 276.9 529.9C203.9 474.2 64 343.9 64 231.7L64 229.1C64 155.6 123.6 96 197.1 96C239.8 96 280 116.5 305 151.1z"/></svg>
                </div>
            </div>
        </header>
    </div>

    <div class="w-full shadow bg-white flex justify-center">
        <nav class="w-2/3 flex items-center justify-between py-4 mb-2">
            <div class="flex items-center relative w-fit rounded-full">
                <button id="leftArrow" class="text-xl absolute -left-1 backdrop-blur bg-red-300/5 w-12 h-12 rounded-full p-1 text-red-600 hover:text-black" disabled>
                    &#8592;
                </button>

                <div id="tagContainer" class="flex pl-14 pr-14 gap-3 overflow-hidden rounded-xl w-[800px]">
                    <?php
                    $navbarTags = ['Découverte', 'Science', 'Environnement', 'Technologie', 'Espace', 'Sport', 'Santé', 'Culture', 'Histoire', 'Art', 'Musique', 'Cinéma', 'Littérature'];
                    foreach ($navbarTags as $tag):
                        $isSelected = (isset($_GET['tag']) && strtolower($_GET['tag']) === strtolower($tag));
                        $href = $isSelected ? '?' : '?tag=' . urlencode($tag);
                        ?>
                        <a href="<?= $href ?>"
                            class="px-3 py-1 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 whitespace-nowrap min-w-fit border-2 trans-fast <?= $isSelected ? 'border-red-600' : '' ?>">
                            #<?= htmlspecialchars($tag) ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <button id="rightArrow" class="text-xl absolute -right-1 backdrop-blur bg-red-300/5 w-12 h-12 rounded-full p-1 text-red-600 hover:text-black">
                    &#8594;
                </button>
            </div>

            <form method="GET" action="" class="flex relative w-fit items-center gap-2">
                <input type="text" name="search" placeholder="Rechercher un article..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                    class="border-b-2 pr-3 trans-fast hover:border-b-black py-1 focus:outline-none" />

                <button type="submit text-red-500">
                    <div alt="Search" class="w-10 cursor-pointer fill-current text-gray-400 rounded-full p-2 -top-1 absolute right-0 h-10 hover:opacity-80">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free 7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M480 272C480 317.9 465.1 360.3 440 394.7L566.6 521.4C579.1 533.9 579.1 554.2 566.6 566.7C554.1 579.2 533.8 579.2 521.3 566.7L394.7 440C360.3 465.1 317.9 480 272 480C157.1 480 64 386.9 64 272C64 157.1 157.1 64 272 64C386.9 64 480 157.1 480 272zM272 416C351.5 416 416 351.5 416 272C416 192.5 351.5 128 272 128C192.5 128 128 192.5 128 272C128 351.5 192.5 416 272 416z"/></svg>
                    </div>
                </button>
            </form>
        </nav>
    </div>

    <div class="w-full flex mb-32 h-full justify-center relative">
        <main class="container w-2/3 mt-8 p-4 h-full trans-fast" id="articlesDiv">
            <div class="w-fit relative">
                <hr class="w-full h-3 z-0 absolute bg-yellow-400 translate-y-5">
                <h2 class="text-2xl mb-10 z-10 relative">Nos Recommandations pour <span
                        class="font-bold ml-1">VOUS</span> !</h2>
            </div>
            <p>
                <?php
                if (empty($recommendations)): ?>
                <p>Aucune recommandation trouvée.</p>
            <?php else: ?>
                <ul class="gap-4 grid grid-cols-3">
                    <?php foreach ($recommendations as $articleId => $score):
                        $article = null;
                        foreach ($articles as $a) {
                            if ($a['id'] == $articleId) {
                                $article = $a;
                                break;
                            }
                        }
                        if (!$article)
                            continue;
                        ?>
                        <li class="cursor-pointer bg-white p-4 shadow rounded-lg 
                       hover:border-red-500 transition
                       border-2 border-white" onclick="openArticleModal(
                    <?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>,
                    <?= htmlspecialchars($_SESSION['user']['id']) ?>
                )">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-bold truncate w-3/4">
                                    <?= htmlspecialchars($article['titre']) ?>
                                </h3>
                                <span class="text-sm text-gray-500">
                                    <?= htmlspecialchars($article['date_publication']) ?>
                                </span>
                            </div>
                            <p class="text-gray-700 mb-3">
                                <?= htmlspecialchars($article['resumee']) ?>
                            </p>
                            <small class="text-gray-500">
                                Score recommandé : <?= number_format($score, 4) ?>
                            </small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            </p>
            </ul>
            <div class="w-fit relative mt-14">
                <hr class="w-full h-3 z-0 absolute bg-yellow-400 translate-y-5">
                <h2 class="text-2xl mb-10 z-10 relative">Liste des articles</h2>
            </div>
            <ul class="gap-4 grid grid-cols-3">
                <?php foreach ($articles as $article): ?>
                    <li class="cursor-pointer bg-white p-4 shadow rounded-lg hover:border-red-500 transition border-2 border-white"
                        onclick="openArticleModal(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($_SESSION['user']['id']) ?>)">

                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-bold truncate w-3/4"><?= htmlspecialchars($article['titre']) ?></h3>
                            <span class="text-sm text-gray-500"><?= htmlspecialchars($article['date_publication']) ?></span>
                        </div>
                        <p class="text-gray-700 mb-3"><?= htmlspecialchars($article['resumee']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>

        <main class="container w-2/3 mt-8 p-4 absolute h-full opacity-0 translate-x-[1000px] trans-fast"
            id="likedArticlesDiv">
            <div class="w-fit relative">
                <hr class="w-full h-3 z-0 absolute bg-yellow-400 translate-y-5">
                <h2 class="text-2xl mb-10 z-10 relative">Liste des articles Likés</h2>
            </div>
            <ul class="gap-4 grid grid-cols-3">
                <?php foreach ($articlesLiked as $article): ?>
                    <li class="cursor-pointer bg-white p-4 shadow rounded-lg hover:border-red-500 transition border-2 border-white"
                        onclick="openArticleModal(<?= htmlspecialchars(json_encode($article), ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($_SESSION['user']['id']) ?>)">

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
    <div id="articleModal" class="hidden mb-32 flex justify-center items-center z-50">
        <div class="bg-white rounded-xl shadow-lg z-10 max-w-3xl w-full p-10 relative">

            <button id="LikeButton"
                onclick="LikedArticleModal(<?= htmlspecialchars($_SESSION['user']['id']) ?>, currentArticleId)"
                class="absolute right-10 top-10 border-red-600 border bg-red-100 text-red-600 px-4 py-2 rounded-md hover:bg-red-200">
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

    <footer class="w-full px-52">
        <div class="bg-[#2c3131] rounded-t-xl w-full text-white text-center p-4 mt-8">
            <p>&copy; <?= date("Y") ?> - Workshop</p>
        </div>
    </footer>

</body>

</html>