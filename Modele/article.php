<?php
$stmt = $pdo->query("SELECT id, titre, contenu FROM articles");

echo "<h2>Liste des articles</h2>";
echo "<ul>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li><strong>" . htmlspecialchars($row['titre']) . "</strong> - " 
         . htmlspecialchars($row['contenu']) . "</li>";
}
echo "</ul>";
