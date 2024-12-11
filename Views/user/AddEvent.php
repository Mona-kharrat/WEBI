<?php
session_start();

// Vérification de la session utilisateur
if (!isset($_SESSION['user']['id'])) {
    header("Location: ../authentification\Authentification.php");
    exit();
}

// Récupérer les erreurs de session et les données du formulaire
$event_errors = $_SESSION['event_errors'] ?? [];
$formData = $_SESSION['formData'] ?? [];
echo "ID utilisateur connecté : " . $_SESSION['user']['id'];

// Nettoyer les erreurs après leur affichage
unset($_SESSION['event_errors']);
unset($_SESSION['formData']);
?>
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

            <!-- Affichage des erreurs globales -->
            <?php if (!empty($event_errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($event_errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="eventForm" action="/webi/Controllers/EventController.php?action=add" method="POST" enctype="multipart/form-data">
                <!-- Titre -->
                <div class="mb-3">
                    <label for="title" class="form-label">Titre de l'évènement</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($formData['title'] ?? '') ?>" >
                    <?php if (isset($event_errors['title'])): ?>
                        <div class="text-danger"><?= $event_errors['title'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" ><?= htmlspecialchars($formData['description'] ?? '') ?></textarea>
                    <?php if (isset($event_errors['description'])): ?>
                        <div class="text-danger"><?= $event_errors['description'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Date -->
                <div class="mb-3">
                    <label for="date" class="form-label">Date de l'évènement</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($formData['date'] ?? '') ?>" >
                    <?php if (isset($event_errors['date'])): ?>
                        <div class="text-danger"><?= $event_errors['date'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Heure -->
                <div class="mb-3">
                    <label for="time" class="form-label">Heure de l'évènement</label>
                    <input type="time" class="form-control" id="time" name="time" value="<?= htmlspecialchars($formData['time'] ?? '') ?>" >
                    <?php if (isset($event_errors['time'])): ?>
                        <div class="text-danger"><?= $event_errors['time'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Lieu -->
                <div class="mb-3">
                    <label for="location" class="form-label">Lieu</label>
                    <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($formData['location'] ?? '') ?>" >
                    <?php if (isset($event_errors['location'])): ?>
                        <div class="text-danger"><?= $event_errors['location'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Catégorie -->
                <div class="mb-3">
                    <label for="category" class="form-label">Catégorie</label>
                    <select class="form-select" id="category" name="category">
                        <option value="Conférence" <?= isset($formData['category']) && $formData['category'] === 'Conférence' ? 'selected' : '' ?>>Conférence</option>
                        <option value="Concert" <?= isset($formData['category']) && $formData['category'] === 'Concert' ? 'selected' : '' ?>>Concert</option>
                        <option value="Sport" <?= isset($formData['category']) && $formData['category'] === 'Sport' ? 'selected' : '' ?>>Sport</option>
                        <option value="Autre" <?= isset($formData['category']) && $formData['category'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                    <?php if (isset($event_errors['category'])): ?>
                        <div class="text-danger"><?= $event_errors['category'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image de l'évènement</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <?php if (isset($event_errors['image'])): ?>
                        <div class="text-danger"><?= $event_errors['image'] ?></div>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn btn-secondary w-100">Ajouter l'évènement</button>
            </form>
        </div>
    </div>
</body>
</html>
