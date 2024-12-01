<?php
session_start();

// Récupération des erreurs et des données de formulaire
// Vérifier si l'ID de l'utilisateur est défini dans la session
$personneId = $_SESSION['user_id'] ?? null;
$errors = $_SESSION['errors'] ?? [];
$formData = $_SESSION['formData'] ?? [];

// Nettoyage des erreurs et données de formulaire après utilisation
unset($_SESSION['errors']);
unset($_SESSION['formData']);

?>
<?php include '../../navbar.html'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Évènement</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,400&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <link rel="stylesheet" href="AddEvent.css">
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
            
            <form id="eventForm" action="/webi/Controllers/EventController.php?action=addEvent" method="POST" enctype="multipart/form-data">
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
        </div>
    </div>
    <script>
        // Si l'ID de l'utilisateur existe dans la session, afficher l'ID dans la console
        <?php if ($personneId): ?>
            console.log("ID de l'utilisateur connecté : <?php echo $personneId; ?>");
        <?php else: ?>
            console.log("Aucun ID utilisateur trouvé dans la session.");
        <?php endif; ?>
    </script>
</body>
</html>
