<?php
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utilisateurs VALUES ('$username', '$password')");
    $stmt->execute();

    header('Location: login.php');
    exit();
}

// Formulaire d'inscription
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <!-- Lien vers votre fichier CSS -->
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <form method="post" action="#">
    <h2>Inscription</h2><br>
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" name="username" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="S'inscrire">
        <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
    </form>
    
</body>
</html>
