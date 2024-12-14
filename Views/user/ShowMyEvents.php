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
    <style>
    .card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
    }
    header {
        color: rgba(2, 77, 112, 0.85); /* même couleur que la navbar en gras */
        padding: 2rem;
        text-align: center;
        position: relative;
        font-weight: bold; /* Ajout de la mise en gras */
        margin-top:50px;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 150px;
        object-fit: cover;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #024d70;
    }

    .card-text {
        margin: 10px 0;
        color: #555;
    }

    .card .btn {
        margin-left: auto;
        margin-right: 5px;
    }

    .card .btn-sm {
        font-size: 0.875rem;
        padding: 5px 10px;
    }

    .card .btn-primary {
        background-color: #0275d8;
        border-color: #0275d8;
    }

    .card .btn-danger {
        background-color: #d9534f;
        border-color: #d9534f;
    }

    .card-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        border-top: 1px solid #ddd;
        padding: 10px;
        background-color: #f9f9f9;
    }

    </style>

</head>
<body>


    <div id="navbar-container"></div> 
    <div class="container my-5">
    <header>
        <h2>Mes événements créés</h2>
    </header>
    <div class="d-flex flex-wrap justify-content-center gap-4" id="eventsList">
        <?php
        if (!empty($events)) {
            foreach ($events as $event) {
                ?>
                <div class="card" style="width: 18rem;">
                    <img src="../<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top img-fluid" alt="Événement">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                        <p class="card-text">
                            <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($event['date']); ?><br>
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                        </p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <!-- Bouton de modification -->
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $event['id']; ?>">
                            Modifier
                        </button>
                        <!-- Bouton de suppression -->
                        <form method="POST" action="\webi\Controllers\EventController.php?action=delete" style="display: inline;">
                            <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>

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