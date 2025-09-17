<?php
$host = 'localhost'; // ou 127.0.0.1
$dbname = 'Workshop';
$username = 'root';
$password = ''; // Mot de passe vide par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Pour afficher les erreurs PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css" /> 
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" required><br><br>

        <label>Mot de passe :</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
</body>
</html>
