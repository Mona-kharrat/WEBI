<?php

require_once '../Models/personneModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function register() {
        // Vérifiez si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirmPassword']);

            // Validation des mots de passe
            if ($password !== $confirmPassword) {
                return $this->renderError("Les mots de passe ne correspondent pas !");
            }

            // Vérification si l'utilisateur existe déjà
            if ($this->userModel->userExists($email)) {
                // Afficher un message d'erreur
                return $this->renderError("L'email existe déjà !");
            }

            // Insertion de l'utilisateur
            if ($this->userModel->insertUser($username, $email, $password)) {
                // Rediriger vers une page de succès ou afficher un message de succès
                header("Location: ../Views/index.php");
                exit();
            } else {
                // Afficher un message d'erreur en cas d'échec
                return $this->renderError("Une erreur s'est produite lors de la création du compte.");
            }
        }
    }

    private function renderError($message) {
        // Cette méthode peut afficher un message d'erreur ou rediriger vers une page d'erreur
        // Vous pouvez personnaliser cette fonction selon vos besoins
        echo '<div class="alert alert-danger">' . htmlspecialchars($message) . '</div>';
    }
}

// Traitement de la requête
$authController = new AuthController();
$authController->register();
?>
