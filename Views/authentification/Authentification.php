<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="Authentification.css">
</head>
</head>
<body>
    <div class="container-fluid vh-100">
        <div class="row h-100">
            
            <div class="col-md-6 d-none d-md-block p-0">
                <img src="../images\Telecommuting-rafiki.png" alt="Image Authentification" class="img-fluid h-100 w-100" style="object-fit: cover;">
            </div>
            
            
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="card p-4 shadow" style="width: 80%; max-width: 400px;">
                    <h2 class="text-center mb-4">Connexion</h2>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="Entrez votre e-mail" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" placeholder="Entrez votre mot de passe" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <a href="reset-password.html" class="text-center mt-3 d-block">Mot de passe oublié ?</a>
                    <a href="inscription.html" class="text-center mt-3 d-block">Créer un compte</a>
                    <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>