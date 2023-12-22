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
    <title>Task Manager</title>
    <!-- Lien vers votre fichier CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>

<body>
    <div class="row">
        <div class="col-md-11">
            <h1 class="display-1 text-center">Task Manager</h1>
        </div>
        <div class="col-md-1">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
    <!-- Formulaire pour ajouter une nouvelle tâche -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" action="#" class="my-4">
                    <div class="form-group row">
                        <label for="new_task" class="col-sm-3 col-form-label ">New Task:</label>
                        <div class="col-sm-6">
                            <input type="text" name="new_task" required class="form-control">
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" name="add_task" class="btn btn-success">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Affichage des tâches -->
        <div class="row">
            <ul class="list-group">
                <?php foreach ($tasks as $task) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="task-text-<?php echo $task['id']; ?>">
                            <?php echo $task['tache']; ?>
                        </div>
                        <div class="task-edit-<?php echo $task['id']; ?>" style="display: none;">
                            <form method="post" action="#">
                                <input type="text" name="edited_task" value="<?php echo $task['tache']; ?>" required class="form-control">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="edit_task" class="btn btn-success btn-sm">Update</button>
                            </form>
                        </div>
                        <div class="btn-group">
                            <!-- Edit button -->
                            <button type="button" class="btn btn-primary btn-sm" onclick="editTask(<?php echo $task['id']; ?>)">Edit</button>

                            <!-- Delete button -->
                            <form method="post" action="#">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <button type="submit" name="delete_task" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <script>
            function editTask(taskId) {
                // Hide task text, show input field for editing
                document.querySelector('.task-text-' + taskId).style.display = 'none';
                document.querySelector('.task-edit-' + taskId).style.display = 'block';
            }
        </script>

</body>

</html>