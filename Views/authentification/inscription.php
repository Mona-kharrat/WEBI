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
                    <form id="signupForm" method="POST" action="/webi/Controllers/personneController.php?action=register">

                        <div class="mb-3">
                            <label for="username" class="form-label">Nom d'utilisateur</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Entrez votre nom d'utilisateur" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #FF725E;">S'inscrire</button>
                    </form>
                    <a href="Authentification.html" class="text-center mt-3 d-block">Déjà un compte ? Connectez-vous</a>
                    <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>
                    <div id="successMessage" class="alert alert-success mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
