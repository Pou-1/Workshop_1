<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: vue_login.php');
    exit();
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
</head>
<body>
    <h1>Bienvenue sur la page d'accueil</h1>
    <p>Utilisateur connecté : <strong><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></strong></p>
    <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
</body>
</html>