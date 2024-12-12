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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $location = $_POST['location'] ?? '';
            $category = $_POST['category'] ?? '';
            $image = $_FILES['image'] ?? null;

            // Validation du formulaire
            $event_errors = $this->validateForm($title, $description, $date, $time, $location, $category);

            // Traitement de l'image
            $imagePath = null;
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $uploadsDir = '../uploads/';
                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0777, true);
                }
                $fileExtension = pathinfo($image['name'], PATHINFO_EXTENSION);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileExtension), $allowedExtensions)) {
                    $uniqueName = uniqid() . '.' . $fileExtension;
                    $targetPath = $uploadsDir . $uniqueName;
                    if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                        $imagePath = '../uploads/' . $uniqueName;
                    } else {
                        $event_errors['image'] = 'Erreur lors du déplacement de l\'image.';
                    }
                } else {
                    $event_errors['image'] = 'Extension de fichier non valide. (jpg, jpeg, png, gif uniquement)';
                }
            } else {
                $event_errors['image'] = 'Une image est requise.';
            }

            // Création de l'événement si pas d'erreurs
            if (empty($event_errors)) {
                try {
                    $eventModel = new EventModel();
                    $eventModel->createEvent($title, $description, $date, $time, $location, $category, $imagePath);
                    header('Location: ../Views/user/ShowMyEvents.php');
                    exit();
                } catch (Exception $e) {
                    echo '<p style="color: red;">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
                }
            } else {
                $_SESSION['event_errors'] = $event_errors;
                $_SESSION['formData'] = compact('title', 'description', 'date', 'time', 'location', 'category');
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
        public function showRegisteredEvents()
{
    $this->checkSession();
    $userId = $_SESSION['user']['id'];

    if (empty($userId)) {
        echo "ID utilisateur invalide.";
        return;
    }

    $eventModel = new EventModel();
    $events = $eventModel->getUserRegisteredEvents($userId);

    if ($events) {
        require_once '../Views/user/EventInscri.php'; 
    } else {
        echo "Aucun événement inscrit trouvé pour cet utilisateur.";
    }
}

    
        public function register()
        {
            $this->checkSession();
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                $eventId = $data['event_id'];
               
        
                $eventModel = new EventModel();
        
                if ($eventModel->isEventExists($eventId)) {
                    $eventModel->registerUserForEvent($eventId);
                    echo "Inscription réussie!";
                } else {
                    echo "Événement non trouvé.";
                }
            } else {
                echo "Requête invalide.";
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
    public function delete()
    {
        $this->checkSession(); // Assurez-vous que l'utilisateur est connecté

        if (isset($_POST['event_id'])) {
            $eventId = intval($_POST['event_id']);
            $userId = $_SESSION['user']['id'];

            $eventModel = new EventModel();
            $success = $eventModel->deleteEventById($eventId, $userId);

            if ($success) {
                $_SESSION['message'] = "Événement supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression de l'événement.";
            }
        } else {
            $_SESSION['error'] = "ID d'événement manquant.";
        }

        header("Location: ../Views/user/ShowMyEvents.php");
        exit();
    }
    public function update()
    {
        $this->checkSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
            $eventId = $_POST['event_id'];
            $title = $_POST['title'] ?? '';
            $date = $_POST['date'] ?? '';
            $location = $_POST['location'] ?? '';
            $image = $_FILES['image'] ?? '';
            $userId = $_SESSION['user']['id'];

            // Validation du formulaire
            $event_errors = $this->validateForm2($title, '', $date, '', $location, '');

            

            // Si pas d'erreur, mise à jour de l'événement
            if (empty($event_errors)) {
                
                $eventModel = new EventModel();
                $eventModel->updateEvent($title, $date, $location,$eventId, $userId);

                $_SESSION['message'] = "Événement modifié avec succès.";
                header('Location: ../Views/user/ShowMyEvents.php');
                exit();
            } else {
                $_SESSION['event_errors'] = $event_errors;
                $_SESSION['formData'] = compact('title', 'date', 'location', );
                header("Location: ../Views/user/ShowMyEvents.php");
                exit();
            }
        }
    }
     private function validateForm2($title, $description, $date, $time, $location, $category)
    {
        $event_errors = [];

        if (empty($title)) $event_errors[] = "Le titre est requis.";
        if (empty($date)) $event_errors[] = "La date est requise.";
        if (empty($location)) $event_errors[] = "Le lieu est requis.";

        return $event_errors;
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
            case 'update':
                $controller->update();
                break;
                case 'delete':
                    $controller->delete();
                    break;
        default:
            echo "Action non valide.";
            break;
    }
}

?>
