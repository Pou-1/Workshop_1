<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
?>

<?php require_once "../Modele/connexion.php"; ?>
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
    <?php require_once "../Modele/article.php"; ?>
  </main>

  <footer class="bg-gray-200 text-center p-4 mt-8">
    <p>&copy; <?= date("Y") ?> - Workshop</p>
  </footer>

</body>
</html>
