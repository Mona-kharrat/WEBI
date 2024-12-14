
<?php
require_once '../../Models/personneModel.php';

$model = new personneModel();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['email'])) {
    $email = $data['email'];

    try {
        $result = $model->resetPassword($email, 'nouveau_mot_de_passe');
        if ($result) {
            echo json_encode(['success' => true, 'showPasswordField' => true]);
        } else {
            echo json_encode(['success' => false, 'showPasswordField' => false, 'error' => 'Email non trouvé ou mise à jour échouée.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'showPasswordField' => false, 'error' => $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Authentification.css">
</head>
<body>
<div id="navbar-container"></div>

    <div class="container-fluid vh-100">
        <div class="row h-100">
          
            <div class="col-md-11 d-flex justify-content-center align-items-center">
                <div class="card p-3 shadow" style="width: 80%; max-width: 400px;">
                    <h2 class="text-center mb-4">Réinitialiser le mot de passe</h2>
                    <form id="resetForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="Entrez votre e-mail" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                    </form>
                    <a href="Authentification.html" class="text-center mt-3 d-block">Retour à la connexion</a>
                    <div id="successMessage" class="alert alert-success mt-3 d-none"></div>
                    <div id="errorMessage" class="alert alert-danger mt-3 d-none"></div>

                    <!-- Champ pour saisir le nouveau mot de passe -->
                    <div id="newPasswordField" class="mb-3 d-none">
                        <label for="newPassword" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="newPassword" placeholder="Entrez votre nouveau mot de passe" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('navbar_user.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            })
            .catch(error => console.log('Erreur lors du chargement de la navbar:', error));

        document.getElementById('resetForm').addEventListener('submit', function(event) {
            event.preventDefault();  // Empêche l'envoi normal du formulaire

            const email = document.getElementById('email').value;

            fetch('reset-password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').classList.remove('d-none');
                    document.getElementById('successMessage').textContent = 'Email envoyé avec succès !';
                    // Afficher le champ de nouveau mot de passe
                    document.getElementById('newPasswordField').classList.remove('d-none');
                } else {
                    document.getElementById('errorMessage').classList.remove('d-none');
                    document.getElementById('errorMessage').textContent = data.error;
                }
            })
            .catch(error => console.log('Erreur:', error));
        });
    </script>
</body>
</html>
