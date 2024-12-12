<?php
session_start();

require_once '../Models/EventModel.php';

class EventController
{
    // Vérifie si l'utilisateur est connecté
    private function checkSession()
    {
        if (!isset($_SESSION['user']['id'])) {
            header("Location: ../Views/authentification/Authentification.php");
            exit();
        }
    }

    public function add()
    {
        $this->checkSession();
            // Vérification de la session utilisateur
            if (!isset($_SESSION['user']['id'])) {
                echo "Session user_id non définie. Veuillez vous connecter.";
                header("Location: ../Views/authentification/Authentification.php");
                exit();
            }
        
            // Vérification du formulaire
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $title = $_POST['title'] ?? '';
                $description = $_POST['description'] ?? '';
                $date = $_POST['date'] ?? '';
                $time = $_POST['time'] ?? '';
                $location = $_POST['location'] ?? '';
                $category = $_POST['category'] ?? '';
                $image = $_FILES['image'] ?? '';
        
                // Validation du formulaire
                $event_errors = $this->validateForm($title, $description, $date, $time, $location, $category);
        
                // Vérification de l'extension de l'image
                if ($image && $image['error'] === UPLOAD_ERR_OK) {
                    $allowedExtensions = ['png', 'jpg', 'jpeg'];
                    $fileInfo = pathinfo($image['name']);
                    $fileExtension = strtolower($fileInfo['extension']);
        
                    if (!in_array($fileExtension, $allowedExtensions)) {
                        $event_errors['image'] = 'Seules les images PNG, JPG et JPEG sont autorisées.';
                    }
                } elseif ($image && $image['error'] !== UPLOAD_ERR_OK) {
                    $event_errors['image'] = 'Une erreur est survenue lors du téléchargement de l\'image.';
                } elseif (empty($image['name'])) {
                    $event_errors['image'] = 'Une image est requise.';
                }
        
                // Si pas d'erreur, création de l'événement
                if (empty($event_errors)) {
                    try {
                        $eventModel = new EventModel();
                        $eventModel->createEvent($title, $description, $date, $time, $location, $category, $image);
                        header('Location: ../Views/user/ShowMyEvents.php');
                        exit();
                    } catch (Exception $e) {
                        echo '<p style="color: red;">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
                    }
                } else {
                    // Affichage des erreurs
                    $_SESSION['event_errors'] = $event_errors;
                    $_SESSION['formData'] = compact('title', 'description', 'date', 'time', 'location', 'category', 'image');
                    header("Location: ../Views/user/AddEvent.php");
                    exit();
                }
            }
        }
        
        // Validation des champs du formulaire
        private function validateForm($title, $description, $date, $time, $location, $category)
        {
            $event_errors = [];
    
            if (empty($title)) $event_errors[] = "Le titre est requis.";
            if (empty($description)) $event_errors[] = "La description est requise.";
            if (empty($date)) $event_errors[] = "La date est requise.";
            if (empty($time)) $event_errors[] = "L'heure est requise.";
            if (empty($location)) $event_errors[] = "Le lieu est requis.";
            if (empty($category)) $event_errors[] = "La catégorie est requise.";
    
            return $event_errors;
        }
    
    public function register()
   {
    $this->checkSession();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
        $eventId = $_POST['event_id'];
        $userId = $_SESSION['user']['id'];

        $eventModel = new EventModel();
        $eventModel->registerUserForEvent($eventId, $userId); // Méthode pour enregistrer l'inscription

        echo "Inscription réussie!";
    } else {
        echo "Aucune action valide.";
    }
}


    public function showMyEvents()
    {
        $this->checkSession();
        $userId = $_SESSION['user']['id'];
    
        if (empty($userId)) {
            echo "ID utilisateur invalide.";
            return;
        }
    
        $eventModel = new EventModel();
        $events = $eventModel->getUserEvents($userId);
    
        var_dump($events); // Debug
    
        if ($events) {
            require_once '../Views/user/ShowMyEvents.php';
        } else {
            echo "Aucun événement trouvé pour cet utilisateur.";
        }
    }

    public function showAllEvents()
    {
        $this->checkSession();

        $eventModel = new EventModel();
        $events = $eventModel->getAllEvents();

        if ($events) {
            require_once '../Views/user/ShowAllEvents.php';
        } else {
            echo "Aucun événement trouvé.";
        }
    }
}
if (isset($_GET['action'])) {
    $controller = new EventController();
    switch ($_GET['action']) {
        case 'add':
            $controller->add();
            break;
        case 'showMyEvents':
            $controller->showMyEvents();
            break;
        case 'showAllEvents':
            $controller->showAllEvents();
            break;
        case 'register':
            $controller->register();
            break;
        default:
            echo "Action non valide.";
            break;
    }
}

?>
