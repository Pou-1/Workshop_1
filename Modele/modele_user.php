<?php
require_once 'connexion.php';

// Insère un nouvel utilisateur dans la base
function insertUser($nom, $prenom, $email, $mdp_hash, $tel) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, mdp, tel) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$nom, $prenom, $email, $mdp_hash, $tel]);
}

// Vérifie si un email existe déjà
function emailExists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch() !== false;
}
