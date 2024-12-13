<?php
require_once '../vendor/autoload.php'; 
require_once '../Models/personneModel.php';
use PHPMailer\PHPMailer\PHPMailer;
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
}

if (isset($_GET['action'])){
    $authController = new AuthController();
    switch ($_GET['action']){
        case 'login':
            $authController->login();
            break;
            case 'register':
                $authController->register();
                break;
                case 'logout':
                    $authController->logout();
                    break;

    }
    
}


?>
