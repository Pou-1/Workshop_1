<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Inscription</h2>
        <form method="post" action="../Controlleur/singin.php" class="space-y-4">
            <div>
                <label class="block text-gray-700">Nom :</label>
                <input type="text" name="nom" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-gray-700">Prénom :</label>
                <input type="text" name="prenom" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-gray-700">Email :</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-gray-700">Mot de passe :</label>
                <input type="password" name="mdp" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div>
                <label class="block text-gray-700">Téléphone :</label>
                <input type="tel" name="tel" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">S'inscrire</button>
        </form>
        <?php
        // Ces variables doivent être définies par le contrôleur
        if (!empty($erreur)) echo '<p class="mt-4 text-red-600 text-center">'.$erreur.'</p>';
        if (!empty($success)) echo '<p class="mt-4 text-green-600 text-center">'.$success.'</p>';
        ?>
    </div>
</body>
</html>