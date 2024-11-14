<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Authentification.css">
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            <div class="col-md-6 d-none d-md-block p-0">
                <img src="../images/Mobile login-pana.png" alt="Image Inscription" class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card p-4 shadow" style="width: 80%; max-width: 400px;">
                    <h2 class="text-center mb-4">Inscription</h2>

                    <?php
                    session_start();
                    // Récupérer les erreurs et les données de formulaire depuis la session
                    $errors = $_SESSION['errors'] ?? [];
                    $formData = $_SESSION['formData'] ?? [];
                    session_unset(); // Nettoyer la session après utilisation
                    ?>

                    <form id="signupForm" method="POST" action="../Controllers/personneController.php?action=register">

                        <?php if (!empty($errors['general'])) : ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($errors['general']); ?></div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required value="<?php echo htmlspecialchars($formData['username'] ?? '', ENT_QUOTES); ?>">
                            <?php if (!empty($errors['username'])) : ?>
                                <div class="text-danger"><?php echo htmlspecialchars($errors['username']); ?></div>
                            <?php endif; ?>
                        </div>

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

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                            <?php if (!empty($errors['confirmPassword'])) : ?>
                                <div class="text-danger"><?php echo htmlspecialchars($errors['confirmPassword']); ?></div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn w-100" style="background-color: #FF725E;">S'inscrire</button>
                    </form>

                    <a href="Authentification.php" class="text-center mt-3 d-block">Déjà un compte ? Connectez-vous</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
