<?php
session_start();

require_once '../Models/EventModel.php';

class EventController {
    private $eventModel;

    public function __construct() {
        $this->eventModel = new EventModel();
    }

    // Fonction pour traiter l'ajout d'un événement
    public function addEvent() {
        // Initialisation des erreurs
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $date = trim($_POST['date']);
            $time = trim($_POST['time']);
            $location = trim($_POST['location']);
            $category = trim($_POST['category']);
            
            // Vérification que les champs obligatoires sont remplis
            if (empty($title)) {
                $errors[] = "Le titre est requis.";
            }
            if (empty($description)) {
                $errors[] = "La description est requise.";
            }
            if (empty($date)) {
                $errors[] = "La date est requise.";
            }
            if (empty($time)) {
                $errors[] = "L'heure est requise.";
            }
            if (empty($location)) {
                $errors[] = "Le lieu est requis.";
            }

            // Gestion de l'image
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // Vérification du type de fichier
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                if (!in_array($fileType, $allowedTypes)) {
                    $errors[] = "Le fichier doit être une image JPEG, PNG ou GIF.";
                }

                // Vérification de la taille du fichier (par exemple, 5Mo max)
                if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
                    $errors[] = "L'image ne doit pas dépasser 5 Mo.";
                }

                if (empty($errors)) {
                    $image = 'uploads/' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image);
                }
            } elseif (empty($_FILES['image']['name'])) {
                $errors[] = "Veuillez télécharger une image.";
            }

            // Vérification de l'existence de l'ID de l'utilisateur dans la session
            if (!isset($_SESSION['user_id'])) {
                $errors[] = "Vous devez être connecté pour ajouter un événement.";
            }

            // Si pas d'erreurs, ajout de l'événement
            if (empty($errors)) {
                // Récupérer l'ID de l'utilisateur à partir de la session
                $user_id = $_SESSION['user_id'];

                // Ajouter l'événement en passant l'ID de l'utilisateur
                $eventAdded = $this->eventModel->addEvent($title, $description, $date, $time, $location, $category, $image, $user_id);

                if ($eventAdded) {
                    // Rediriger vers une page de succès ou la liste des événements
                    header("Location: \webi\Views\user\ShowMyEvents.php");
                    exit();
                } else {
                    $errors[] = "Une erreur est survenue lors de l'ajout de l'événement.";
                }
            }
        }

        // Enregistrement des erreurs et données de formulaire dans la session
        $_SESSION['errors'] = $errors;
        $_SESSION['formData'] = $_POST;
        
        // Redirection vers le formulaire avec les erreurs et données
        header("Location:\webi\Views\user\AddEvent.php");
        exit();
    }
}

// Traitement de la demande
$eventController = new EventController();
$eventController->addEvent();
?>
