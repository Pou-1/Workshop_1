<?php
require_once '../Modele/connexion.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    // Sélectionne l'utilisateur par email uniquement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mdp'])) {
        session_start();
        $_SESSION['user'] = $user;

        header('Location: ../Vue/main.php');
        exit();

    } else {
        // Erreur de connexion
        $erreur = "Identifiants incorrects";
        include '../Vue/vue_login.php';
    }
} else {
    include '../Vue/vue_login.php';
}