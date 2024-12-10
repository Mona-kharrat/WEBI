<?php
session_start();

require_once '../Models/EventModel.php';

class EventController
{
    // Méthode pour ajouter un événement
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
            $image = $_FILES['image'] ?? null;

            // Validation du formulaire
            $errors = $this->validateForm($title, $description, $date, $time, $location, $category);

            // Si pas d'erreur, création de l'événement
            if (empty($errors)) {
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
                foreach ($errors as $error) {
                    echo '<p style="color: red;">' . htmlspecialchars($error) . '</p>';
                }
            }
        }
    }

    // Validation des champs du formulaire
    private function validateForm($title, $description, $date, $time, $location, $category)
    {
        $errors = [];

        if (empty($title)) $errors[] = "Le titre est requis.";
        if (empty($description)) $errors[] = "La description est requise.";
        if (empty($date)) $errors[] = "La date est requise.";
        if (empty($time)) $errors[] = "L'heure est requise.";
        if (empty($location)) $errors[] = "Le lieu est requis.";
        if (empty($category)) $errors[] = "La catégorie est requise.";

        return $errors;
    }

    // Méthode pour afficher les événements d'un utilisateur
    public function showMyEvents()
    {
        // Vérification de la session utilisateur
        if (!isset($_SESSION['user']['id'])) {
            echo "Veuillez vous connecter pour afficher vos événements.";
            header("Location: ../Views/authentification/Authentification.php");
            exit();
        }

        // Si la session est correcte, afficher l'ID de l'utilisateur
        $userId = $_SESSION['user']['id'];
        echo "Recherche des événements pour l'utilisateur avec ID: " . $userId . "<br>";

        // Instanciation du modèle et récupération des événements
        $eventModel = new EventModel();
        $events = $eventModel->getUserEvents($userId);

        // Vérification des événements récupérés
        if ($events) {
            $_SESSION['events'] = $events;
        } else {
            echo "Aucun événement trouvé pour cet utilisateur.";
        }

        // Redirection vers la vue pour afficher les événements
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
