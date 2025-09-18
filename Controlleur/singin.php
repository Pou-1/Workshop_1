<?php
require_once '../Modele/connexion.php';
require_once '../Modele/modele_user.php';
require_once '../Modele/modele_banque.php';

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
    // Récupère les champs utilisateur
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mdp'] ?? '';
    $tel = $_POST['tel'] ?? '';

    // Récupère les champs bancaires
    $titulaire = $_POST['titulaire'] ?? '';
    $numero_carte = $_POST['numero_carte'] ?? '';
    $expiration = $_POST['expiration'] ?? '';
    $cvv = $_POST['cvv'] ?? '';
    $type_carte = $_POST['type_carte'] ?? '';

    // Vérifie que tous les champs sont remplis
    if (
        empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($tel) ||
        empty($titulaire) || empty($numero_carte) || empty($expiration) || empty($cvv)
    ) {
        $erreur = "Tous les champs sont obligatoires.";
        include '../Vue/vue_singin.php';
        exit();
    }

    // Vérifie si l'email existe déjà
    if (emailExists($email)) {
        $erreur = "Cet email existe déjà.";
        include '../Vue/vue_singin.php';
        exit();
    }

    // Hashage du mot de passe
    $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);

    try {
        // Insère l'utilisateur
        if (insertUser($nom, $prenom, $email, $mdp_hash, $tel)) {
            // Récupère l'id du nouvel utilisateur
            $userId = $pdo->lastInsertId();

            // Insère la carte bancaire
            if (ajouterCarteBancaire($userId, $titulaire, $numero_carte, $expiration, $cvv, $type_carte)) {
                // Redirection après succès
                header('Location: ../Vue/vue_login.php');
                exit();
            } else {
                $erreur = "Utilisateur créé, mais erreur lors de l'ajout de la carte bancaire.";
            }
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