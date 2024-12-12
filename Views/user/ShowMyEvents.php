<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header("Location: /webi/Views/Authentification.php");
    exit();
}
require_once "../../Models/EventModel.php";
$eventModel = new EventModel();
$userId = $_SESSION['user']['id'];
$events = $eventModel->getUserEvents($userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements inscrits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div id="navbar-container"></div> 
    <div class="container my-5">
        <h2 class="mb-4">Mes événements inscrits</h2>
        <div class="row" id="eventsList">
            <?php
            if (!empty($events)) {
                foreach ($events as $event) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="../<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="Événement">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($event['date']); ?><br>
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                                
                                <!-- Formulaire pour la modification -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $event['id']; ?>">
                                    Modifier
                                </button>
                                

                                <!-- Modal pour la modification -->
                                <div class="modal fade" id="editModal<?php echo $event['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $event['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editModalLabel<?php echo $event['id']; ?>">Modifier l'événement</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="\webi\Controllers\EventController.php?action=update">
                                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                                    <div class="mb-3">
                                                        <label for="title<?php echo $event['id']; ?>" class="form-label">Titre</label>
                                                        <input type="text" class="form-control" id="title<?php echo $event['id']; ?>" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="date<?php echo $event['id']; ?>" class="form-label">Date</label>
                                                        <input type="date" class="form-control" id="date<?php echo $event['id']; ?>" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="location<?php echo $event['id']; ?>" class="form-label">Lieu</label>
                                                        <input type="text" class="form-control" id="location<?php echo $event['id']; ?>" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>
                                                    </div>
                                                    <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulaire pour la suppression -->
                                <form method="POST" action="\webi\Controllers\EventController.php?action=delete">
                                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                        Supprimer
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>Aucun événement trouvé.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        fetch('navbar_user.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            })
            .catch(error => console.log('Erreur lors du chargement de la navbar:', error));
    </script>
</body>
</html>
