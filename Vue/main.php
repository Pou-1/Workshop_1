<?php
// inclure la connexion
require_once "../Modele/connexion.php";

// démarrer la sortie HTML après la connexion
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Workshop</title>
    <link rel="stylesheet" href="../public/css/styles.css"> <!-- si tu as un CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            color: #333;
            margin: 2rem;
        }
        header {
            background: #007bff;
            color: white;
            padding: 1rem;
            border-radius: 8px;
        }
        h1 {
            margin: 0;
        }
        .content {
            margin-top: 2rem;
        }
        footer {
            margin-top: 3rem;
            padding: 1rem;
            background: #eee;
            text-align: center;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bienvenue sur Workshop</h1>
    </header>

    <div class="content">
        <?php
        // inclure l'affichage des articles
        require_once "../Modele/articles.php";
        ?>
    </div>

    <footer>
        <p>&copy; <?= date("Y") ?> - Workshop</p>
    </footer>
</body>
</html>
