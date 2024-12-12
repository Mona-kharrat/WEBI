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

        // Le reste du code de la méthode add...
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
