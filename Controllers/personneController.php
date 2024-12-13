<?php
require_once '../vendor/autoload.php'; 
require_once '../Models/personneModel.php';
use PHPMailer\PHPMailer\PHPMailer;
class AuthController {
    private $personneModel;

    public function __construct() {
        $this->personneModel = new personneModel();
    }
    private function checkSession()
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: ../Views/authentification/Authentification.php");
            exit();
        }
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
            if ($this->personneModel->userExists($email)) {
                $errors['email'] = "L'email existe déjà !";
            }

            // S'il n'y a pas d'erreurs, procédez à l'insertion
            if (empty($errors)) {
                if ($this->personneModel->insertUser($username, $email, $password)) {
                    // Enregistrez les informations de l'utilisateur dans la session après l'inscription
                    session_start();
                    $_SESSION['user'] = [
                        'username' => $username,
                        'email' => $email,
                        'id' => $this->personneModel->getUserIdByEmail($email) // Assurez-vous d'avoir une méthode pour récupérer l'ID
                    ];

                    

                    $this->sendConfirmationEmail($email,$username);

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
    public function showAllusers()
    {
        $this->checkSession();

        $personneModel = new personneModel();
        $users = $personneModel->getUsers();

        if ($users) {
            require_once '../Views/admin/gestion_user.php';
        } else {
            echo "Aucun user trouvé.";
        }
    }
    private function sendConfirmationEmail($email, $username) {
        echo "<script>console.log('Test : Envoi de l\'email échoué');</script>";

        $mail = new PHPMailer(true);
        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();  // Utiliser SMTP
            $mail->Host = 'smtp.gmail.com';  // Hôte SMTP de Gmail
            $mail->SMTPAuth = true;  // Activer l'authentification SMTP
            $mail->Username = 'mariembouaziz.mb@gmail.com';  // Votre adresse Gmail
            $mail->Password = 'Mar40720Ad';  // Votre mot de passe Gmail ou un mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Utiliser STARTTLS pour sécuriser la connexion
            $mail->Port = 587;  // Le port SMTP de Gmail pour STARTTLS
    
            // Expéditeur et destinataire
            $mail->setFrom('mariembouaziz.mb@gmail.com', 'Mon Application');
            $mail->addAddress($email, $username);  // Adresse email du destinataire
    
            // Contenu de l'email
            $mail->isHTML(true);  // Envoi d'email au format HTML
            $mail->Subject = 'Inscription à l\'événement';
            $mail->Body = 'Bonjour, vous êtes inscrit à un événement.';
    
            // Envoi de l'email
            if($mail->send()) {
                echo 'Message envoyé avec succès.';
            } else {
                // Envoyer l'erreur à la console JavaScript
                echo "<script>alert('Erreur lors de l\'envoi de l\'email : " . $mail->ErrorInfo . "');</script>";

            }
        } catch (Exception $e) {
            // Envoyer l'erreur à la console JavaScript
            echo "<script>alert('Erreur lors de l\'envoi de l\'email : " . $mail->ErrorInfo . "');</script>";

        }
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
                $user = $this->personneModel->getUserByEmail($email);
    
                // Vérification de l'utilisateur et du mot de passe
                if ($user && password_verify($password, $user['password'])) {
                    // Démarrer la session et enregistrer l'utilisateur dans la session
                    session_start();
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role'] // Récupération du rôle de l'utilisateur
                    ];
    
                    // Vérification du rôle et redirection vers le tableau de bord approprié
                    if ($user['role'] === 'admin') {
                        // Redirection vers le tableau de bord de l'admin
                        header("Location: ../Views/admin/Dashboard.php");
                        exit();
                    } else {
                        // Redirection vers la page des événements de l'utilisateur
                        header("Location: ../Views/user/ShowMyEvents.php");
                        exit();
                    }
                } else {
                    $errors['general'] = "Identifiants incorrects. Veuillez vérifier votre e-mail et votre mot de passe.";
                }
            }
        }
    
        // Enregistrez les erreurs et redirigez
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = ['email' => $email ?? ''];
        header("Location: ../Views/authentification/Authentification.php");
        exit();
    }
    
    public function blockUser($userId) {
        // Appeler la méthode de blocage du modèle
        if ($this->personneModel->blockUser($userId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        // Redirection vers la page d'administration ou vers la liste des utilisateurs
        header("Location: ../Views/admin/gestion_user.php");
        exit();
    }

    // Débloquer un utilisateur
    public function unblockUser($userId) {
        // Appeler la méthode de déblocage du modèle
        if ($this->personneModel->unblockUser($userId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        // Redirection vers la page d'administration ou vers la liste des utilisateurs
        header("Location: ../Views/admin/gestion_user.php");
        exit();
    }
    public function deleteUser($userId) {
        // Appeler la méthode de déblocage du modèle
        if ($this->personneModel->deleteUser($userId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        // Redirection vers la page d'administration ou vers la liste des utilisateurs
        header("Location: ../Views/admin/gestion_user.php");
        exit();
    }
    function logout() {
        // Détaille la session
        $_SESSION = array();
    
        // Si la session existe, la détruire
        if (session_id()) {
            session_destroy();
        }
    
        // Redirige vers la page de connexion ou page d'accueil
        header("Location: ../index.php"); // Ou la page de ton choix
        exit();
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $role = $_POST['role'];
            $userId = $_POST['user_id'];
    
            // Validation du formulaire
            $user_errors = $this->validateForm($role);
    
            // Si pas d'erreur, mise à jour de l'utilisateur
            if (empty($user_errors)) {
                $personneModel = new personneModel();
                $personneModel->updateUser($role, $userId);
    
                $_SESSION['message'] = "Le rôle de l'utilisateur a été modifié avec succès.";
                header('Location: ../Views/admin/gestion_user.php');
                exit();
            } else {
                $_SESSION['user_errors'] = $user_errors;
                $_SESSION['formData'] = compact('role');
                header('Location: ../Views/admin/gestion_user.php');
                exit();
            }
        }
    }
    
    private function validateForm($role)
    {
        $user_errors = [];
    
        if (empty($role)) {
            $user_errors[] = "Le rôle est requis.";
        }
    
        return $user_errors;
    }
}

if (isset($_GET['action'])) {
    $authController = new AuthController();
    switch ($_GET['action']) {
        case 'login':
            $authController->login();
            break;
        case 'register':
            $authController->register();
            break;
        case 'logout':
            $authController->logout();
            break;
        case 'blockUser':
            $userId = (int)$_GET['id'];
            $authController->blockUser($userId);
            break;
        case 'unblockUser':
            $userId = (int)$_GET['id'];
            $authController->unblockUser($userId);
            break;
            case 'deleteUser':
            $userId = (int)$_GET['id'];
            $authController->deleteUser($userId);
            break;
            case 'showAllusers':
            $authController->showAllusers();
            break;
            case 'update':
            $authController->update();
            break;
    }
}



?>
