<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../js/singin.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Inscription</h2>
        <form method="post" action="../Controlleur/singin.php" class="space-y-4" id="inscriptionForm">
            <div id="userFields">
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
                <!-- Flèche pour passer au formulaire bancaire -->
                <button type="button" id="showBankForm" class="w-full flex justify-center items-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                    <span>Suivant</span>
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
            <div id="bankForm" class="hidden">
                    <button type="button" id="backToUserForm" class="mb-4 flex items-center text-blue-600 hover:text-blue-800 transition">
        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        <span>Retour</span>
    </button>
                <h3 class="text-xl font-bold mb-4 text-blue-600 text-center">Informations bancaires</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Titulaire de la carte* :</label>
                        <input type="text" name="titulaire" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-gray-700">Numéro de carte* :</label>
                        <input type="text" name="numero_carte" maxlength="19" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div class="flex space-x-4">
                        <div class="w-1/2">
                            <label class="block text-gray-700">Expiration* (MM/YY) :</label>
                            <input type="text" name="expiration" maxlength="5" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                        </div>
                        <div class="w-1/2">
                            <label class="block text-gray-700">CVV* :</label>
                            <input type="text" name="cvv" maxlength="4" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700">Type de carte :</label>
                        <input type="text" name="type_carte" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">S'inscrire</button>
                </div>
            </div>
        </form>
        <?php
        if (!empty($erreur)) echo '<p class="mt-4 text-red-600 text-center">'.$erreur.'</p>';
        if (!empty($success)) echo '<p class="mt-4 text-green-600 text-center">'.$success.'</p>';
        ?>
    </div>
</body>
</html>