<?php
require_once "connexion.php";

function ajouterCarteBancaire($userId, $titulaire, $numero_carte, $expiration, $cvv, $type_carte) {
    global $pdo;
    $sql = "INSERT INTO carte_bancaire (user_id, titulaire, numero_carte, expiration, cvv, type_carte) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$userId, $titulaire, $numero_carte, $expiration, $cvv, $type_carte]);
}