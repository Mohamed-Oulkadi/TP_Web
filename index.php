<?php
include 'connexion.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Ajout d'une nouvelle tâche
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $newTask = $_POST['new_task'];
    
    $stmt = $pdo->prepare("INSERT INTO taches (tache, user_id) VALUES ('$newTask', '$userId')");
    $stmt->execute();
}

// Modification d'une tâche existante
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_task'])) {
    $taskId = $_POST['task_id'];
    $editedTask = $_POST['edited_task'];

    $stmt = $pdo->prepare("UPDATE taches SET tache = '$editedTask' WHERE id = '$taskId' AND user_id = '$userId'");
    $stmt->execute();
}

// Suppression d'une tâche
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_task'])) {
    $taskId = $_POST['task_id'];

    $stmt = $pdo->prepare("DELETE FROM taches WHERE id = '$taskId' AND user_id = '$userId'");
    $stmt->execute();
}

// Récupération des tâches de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM taches WHERE user_id = '$userId'");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes des Taches</title>
    <!-- Lien vers votre fichier CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>

<!-- Formulaire pour ajouter une nouvelle tâche -->
<form method="post" action="#">
    <label for="new_task">Nouvelle tâche:</label>
    <input type="text" name="new_task" required>
    <button type="submit" name="add_task" class="btn btn-success">Ajouter</button>
</form>

<!-- Affichage des tâches -->
<h1>Liste des tâches</h1>
<div class="row">
    <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?php echo $task['tache']; ?></span>
                
                <!-- Boutons pour modifier et supprimer la tâche -->
                <div class="btn-group">
                    <!-- Formulaire pour modifier la tâche -->
                    <form method="post" action="#">
                        <div class="input-group">
                            <input type="text" name="edited_task" class="form-control" placeholder="Modifier la tâche" required>
                            <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        </div>
                        <button type="submit" name="edit_task" class="btn btn-primary">Modifier</button>
                    </form>

                    <!-- Formulaire pour supprimer la tâche -->
                    <form method="post" action="#">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <button type="submit" name="delete_task" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

    <br>
    <a href="logout.php">Déconnexion</a>

</body>
</html>
