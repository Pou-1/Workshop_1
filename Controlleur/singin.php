<?php
require_once '../Modele/connexion.php';
require_once '../Modele/modele_user.php';

// Active l'affichage des erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$erreur = '';
$success = '';

// Vérifie si la connexion PDO existe
if (!isset($pdo)) {
    die('Erreur : PDO non initialisé');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Affiche les données reçues pour debug
    // echo '<pre>'; print_r($_POST); echo '</pre>';

    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $tel = $_POST['tel'] ?? '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($tel)) {
        $erreur = "Tous les champs sont obligatoires.";
        include '../Vue/vue_singin.php';
        exit();
    }

    // Vérifier si l'email existe déjà via le modèle
    if (emailExists($email)) {
        $erreur = "Cet email existe déjà.";
        include '../Vue/vue_singin.php';
        exit();
    }

    // Hashage du mot de passe
    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

    // Insérer le nouvel utilisateur via le modèle
    try {
        if (insertUser($nom, $prenom, $email, $mdp_hash, $tel)) {
            // Redirection vers la page de connexion après inscription réussie
            header('Location: ../Vue/vue_login.php');
            exit();
        } else {
            $erreur = "Erreur lors de l'inscription.";
        }
    } catch (PDOException $e) {
        $erreur = "Erreur SQL : " . $e->getMessage();
    }
    include '../Vue/vue_singin.php';
    exit();
} else {
    include '../Vue/vue_singin.php';
}