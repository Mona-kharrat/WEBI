<?php
session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: ../authentification\Authentification.php");
    exit();
}

echo "ID utilisateur connecté : " . $_SESSION['user']['id'];
?>
<?php include '../../navbar.html'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Évènement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="AddEvent.css">
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .card {
            margin-top: 80px; 
        }
    </style>
</head>
<body>
    <div id="navbar-container"></div>
    <div class="container-fluid vh-100 d-flex justify-content-center align-items-start"> 
        <div class="card p-4" style="width: 90%; max-width: 900px;">
            <h2 class="text-center mb-4">Créer un Évènement</h2>
            
            <!-- Affichage des erreurs si présentes -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form id="eventForm" action="/webi/Controllers/EventController.php?action=add" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Titre de l'évènement</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($formData['title'] ?? '') ?>" placeholder="Entrez le titre de l'évènement" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" placeholder="Description de l'évènement" required><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Date de l'évènement</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($formData['date'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Heure de l'évènement</label>
                    <input type="time" class="form-control" id="time" name="time" value="<?= htmlspecialchars($formData['time'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($formData['location'] ?? '') ?>" placeholder="Entrez le lieu de l'évènement" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-select" id="category" name="category">
                        <option value="Conférence" <?= isset($formData['category']) && $formData['category'] === 'Conférence' ? 'selected' : '' ?>>Conférence</option>
                        <option value="Concert" <?= isset($formData['category']) && $formData['category'] === 'Concert' ? 'selected' : '' ?>>Concert</option>
                        <option value="Sport" <?= isset($formData['category']) && $formData['category'] === 'Sport' ? 'selected' : '' ?>>Sport</option>
                        <option value="Autre" <?= isset($formData['category']) && $formData['category'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image de l'évènement</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <!-- Affichage du nom de l'image si un fichier a été sélectionné -->
                    <?php if (isset($formData['image']) && $formData['image']): ?>
                        <p class="mt-2">Fichier choisi: <?= htmlspecialchars($formData['image']) ?></p>
                    <?php else: ?>
                        <p class="mt-2">Aucun fichier choisi</p>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-secondary w-100">Publier l'évènement</button>
            </form>
            
            <div id="successMessage" class="alert alert-success mt-3" style="display:none;">
                L'événement a été ajouté avec succès !
            </div>
            <button type="submit" class="btn btn-primary">Ajouter l'évènement</button>
        </form>
    </div>
</body>
</html>
