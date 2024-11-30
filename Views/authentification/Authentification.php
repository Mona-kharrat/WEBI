<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Authentification.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <div class="col-md-6 d-none d-md-block p-0">
                <img src="../../images/Telecommuting-rafiki.png" alt="Image Authentification" class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card p-4 shadow" style="width: 80%; max-width: 400px;">
                    <h2 class="text-center mb-4">Connexion</h2>

                    <?php
                    // Démarrer la session
                    session_start();

                    // Récupérer les erreurs et les données de formulaire de session
                    $errors = $_SESSION['errors'] ?? [];
                    $formData = $_SESSION['formData'] ?? [];

                    // Supprimer les erreurs et données après utilisation
                    session_unset();
                    ?>

                    <?php if (!empty($errors['general'])) : ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                    <?php endif; ?>

                    <form id="loginForm" method="POST" action="\webi\Controllers\personneController.php?action=login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre e-mail" required value="<?php echo htmlspecialchars($formData['email'] ?? '', ENT_QUOTES); ?>">
                            <?php if (!empty($errors['email'])) : ?>
                                <div class="text-danger"><?php echo htmlspecialchars($errors['email']); ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                            <?php if (!empty($errors['password'])) : ?>
                                <div class="text-danger"><?php echo htmlspecialchars($errors['password']); ?></div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>

                    <a href="reset-password.html" class="text-center mt-3 d-block">Mot de passe oublié ?</a>
                    <a href="inscription.php" class="text-center mt-3 d-block">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
