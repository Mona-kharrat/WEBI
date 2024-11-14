<?php

require_once '../Models/personneModel.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function register() {
        // Initialisation du tableau des erreurs
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmPassword = trim($_POST['confirmPassword']);

            // Validation des mots de passe
            if ($password !== $confirmPassword) {
                $errors['password'] = "Les mots de passe ne correspondent pas !";
            }

            // Vérification si l'utilisateur existe déjà
            if ($this->userModel->userExists($email)) {
                $errors['email'] = "L'email existe déjà !";
            }

            // S'il n'y a pas d'erreurs, procédez à l'insertion
            if (empty($errors)) {
                if ($this->userModel->insertUser($username, $email, $password)) {
                    // Enregistrez les informations de l'utilisateur dans la session après l'inscription
                    session_start();
                    $_SESSION['user'] = [
                        'username' => $username,
                        'email' => $email,
                        'id' => $this->userModel->getUserIdByEmail($email) // Assurez-vous d'avoir une méthode pour récupérer l'ID
                    ];

                    // Redirige vers la page de succès
                    header("Location: ../Views/user/ShowMyEvents.php");
                    exit();
                } else {
                    $errors['general'] = "Une erreur s'est produite lors de la création du compte.";
                }
            }
        }

        // Enregistrez les erreurs et les valeurs dans une session
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = [
            'username' => $username ?? '',
            'email' => $email ?? ''
        ];

        // Redirigez vers la page d'inscription pour afficher les erreurs
        header("Location: ../Views/authentification/inscription.php");
        exit();
    }

    public function login() {
        // Initialisation du tableau des erreurs
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            // Validation des champs
            if (empty($email)) {
                $errors['email'] = "Veuillez entrer votre adresse e-mail.";
            }
            if (empty($password)) {
                $errors['password'] = "Veuillez entrer votre mot de passe.";
            }

            // Si aucun champ n'est vide, on vérifie l'utilisateur
            if (empty($errors)) {
                $user = $this->userModel->getUserByEmail($email);

                // Vérification de l'utilisateur et du mot de passe
                if ($user && password_verify($password, $user['password'])) {
                    // Démarrer la session et enregistrer l'utilisateur dans la session
                    session_start();
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email']
                    ];

                    // Rediriger vers la page de l'utilisateur ou la page des événements
                    header("Location: ../Views/user/ShowMyEvents.php");
                    exit();
                } else {
                    $errors['general'] = "Identifiants incorrects. Veuillez vérifier votre e-mail et votre mot de passe.";
                }
            }
        }

        // Enregistrez les erreurs et redirigez
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = ['email' => $email ?? ''];

        // Redirection vers la page de connexion pour afficher les erreurs
        header("Location: ../Views/authentification/Authentification.php");
        exit();
    }
}

// Déterminez l'action à effectuer
$authController = new AuthController();
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $authController->login();
} else {
    $authController->register();
}

?>
