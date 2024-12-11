<?php
session_start();

require_once '../Models/EventModel.php';

class EventController
{
    public function add()
    {
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


    public function showMyEvents()
    {
        // Vérification de la session utilisateur
        if (!isset($_SESSION['user']['id'])) {
            echo "Veuillez vous connecter pour afficher vos événements.";
            header("Location: ../Views/authentification/Authentification.php");
            exit();
        }
    
        // Récupérer l'ID de l'utilisateur depuis la session
        $userId = $_SESSION['user']['id'];
    
        // Instanciation du modèle et récupération des événements de l'utilisateur
        $eventModel = new EventModel();
        $events = $eventModel->getUserEvents($userId);
    
        // Vérification si des événements ont été trouvés
        if ($events) {
            // Optionnel : Vous pouvez mettre à jour la session avec les événements récupérés, si vous le souhaitez
            $_SESSION['events'] = $events;
        } else {
            // Aucun événement trouvé, afficher un message approprié
            echo "Aucun événement trouvé pour cet utilisateur.";
            return; // Vous pouvez aussi rediriger ici si nécessaire
        }
    
        // Redirection vers la vue qui affichera les événements
        require_once '../Views/user/ShowMyEvents.php';
    }
    

}

// Gestion des actions via les paramètres GET
if (isset($_GET['action'])) {
    $controller = new EventController();
    switch ($_GET['action']) {
        case 'add':
            $controller->add();
            break;
        case 'showMyEvents':
            $controller->showMyEvents();
            break;
        default:
            // Si l'action n'est pas définie ou est incorrecte, redirigez ou affichez un message d'erreur.
            echo "Action non valide.";
            break;
    }
}
?>
