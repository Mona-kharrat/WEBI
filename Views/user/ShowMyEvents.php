<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user']['id'])) {
    header("Location: /webi/Views/Authentification.php");
    exit();
}

// Récupérer les événements depuis la base de données
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
                                <a href="deleteEvent.php?id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
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
