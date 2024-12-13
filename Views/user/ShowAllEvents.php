<?php
session_start();
require_once '../../Models/EventModel.php';

$eventModel = new EventModel();

// Définir la limite d'affichage par page
$limit = 6;

// Récupérer la page actuelle ou utiliser 1 par défaut
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Obtenir les événements paginés
$events = $eventModel->getAllEvents($page, $limit);

// Calcul du nombre total d'événements
$totalEvents = $eventModel->getTotalEvents(); // Méthode ajoutée pour obtenir le nombre total d'événements

// Calcul du nombre total de pages
$totalPages = ceil($totalEvents / $limit);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorer les Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
          header {
            color: rgba(2, 77, 112, 0.85); /* même couleur que la navbar en gras */
            padding: 2rem;
            text-align: center;
            position: relative;
            font-weight: bold; /* Ajout de la mise en gras */
            margin-top:60px;
        }
    </style>
</head>
<body>
    <div id="navbar-container"></div> 

    <div class="container my-5">
        <header>
            <h1 class="mb-4">Explorer les Événements</h1>
        </header>

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
                                <button class="btn btn-outline-primary btn-sm register-btn" 
                                        data-id="<?php echo htmlspecialchars($event['id']); ?>" 
                                        data-title="<?php echo htmlspecialchars($event['title']); ?>" 
                                        data-date="<?php echo htmlspecialchars($event['date']); ?>" 
                                        data-location="<?php echo htmlspecialchars($event['location']); ?>">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </button>
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

    <nav>
        <!--nav pagination-->
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php if ($i === $page) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const registerButtons = document.querySelectorAll('.register-btn');

            registerButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const eventTitle = this.getAttribute('data-title');
                    const eventDate = this.getAttribute('data-date');
                    const eventLocation = this.getAttribute('data-location');

                    let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents')) || [];
                    registeredEvents.push({ title: eventTitle, date: eventDate, location: eventLocation });
                    localStorage.setItem('registeredEvents', JSON.stringify(registeredEvents));

                    alert('Vous êtes maintenant inscrit à l\'événement : ' + eventTitle);
                });
            });
        });

        fetch('navbar_user.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            })
            .catch(error => console.log('Erreur lors du chargement de la navbar:', error));

        document.querySelectorAll('.register-btn').forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-id');

                fetch('../../Controllers/EventController.php?action=register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ event_id: eventId })
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Pour afficher le message de confirmation
                    loadEvents(<?php echo $page; ?>); // Recharger uniquement le contenu des événements
                })
                .catch(error => console.error('Erreur lors de l\'inscription :', error));
            });
        });

        document.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('href').split('=')[1];
                loadEvents(page); // Fonction JavaScript pour charger les événements selon la page
            });
        });
//pagination
        function loadEvents(page) {
    fetch(`?page=${page}`)
        .then(response => response.text())
        .then(data => {
            const eventsList = document.getElementById('eventsList');
            eventsList.innerHTML = ''; // Nettoyage uniquement des cartes d'événements
            
            // Récupération seulement des div contenant les cartes
            const newEventsHTML = new DOMParser().parseFromString(data, 'text/html')
                .querySelectorAll('#eventsList > .col-md-4.mb-4');

            // Ajout des nouvelles cartes d'événements
            newEventsHTML.forEach(eventCard => {
                eventsList.appendChild(eventCard.cloneNode(true));
            });
        })
        .catch(error => console.error('Erreur de chargement des événements:', error));
}

    </script>
</body>
</html>
