<?php
session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../authentification\Authentification.php");
    exit();
}

echo "ID utilisateur connecté : " . $_SESSION['user']['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Évènement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Créer un Évènement</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/webi/Controllers/EventController.php?action=add" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Titre de l'évènement</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="mb-3">
                <label for="time" class="form-label">Heure</label>
                <input type="time" class="form-control" id="time" name="time" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Lieu</label>
                <input type="text" class="form-control" id="location" name="location" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Catégorie</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="" disabled selected>Choisissez une catégorie</option>
                    <option value="Conférence">Conférence</option>
                    <option value="Concert">Concert</option>
                    <option value="Sport">Sport</option>
                    <option value="Autre">Autre</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image (optionnelle)</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'évènement</button>
        </form>
    </div>
</body>
</html>
