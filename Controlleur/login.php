<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../Modele/connexion.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $tel = $_POST['tel'] ?? '';

    // Sélectionne l'utilisateur par tous les champs sauf le mot de passe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nom = ? AND prenom = ? AND email = ? AND tel = ?");
    $stmt->execute([$nom, $prenom, $email, $tel]);
    $user = $stmt->fetch();
    var_dump([$nom, $prenom, $email, $tel]); // Affiche les valeurs envoyées
    var_dump($user);

    if ($user && password_verify($mdp, $user['mdp'])) {
        session_start();
        $_SESSION['user'] = $user;

        if (headers_sent($file, $line)) {
            die("Headers already sent in $file on line $line");
        }

        header('Location: /Workshop_1/Vue/vue_accueil.php');
        exit();

    } else {
        // Erreur de connexion
        $erreur = "Identifiants incorrects";
        include '../Vue/vue_login.php';
    }
} else {
    include '../Vue/vue_login.php';
}