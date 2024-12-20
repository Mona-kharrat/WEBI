<?php
session_start();
require_once '../../Models/EventModel.php';

$eventModel = new EventModel();

// Définir la limite d'affichage par page
$limit = 6;

// Récupérer la page actuelle ou utiliser 1 par défaut
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$location = isset($_GET['location']) ? $_GET['location'] : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
$events = $eventModel->getAllEvents($page, $limit, $location, $date);

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
        color: rgba(2, 77, 112, 0.85);
        padding: 2rem;
        text-align: center;
        font-weight: bold;
        margin-top: 50px;
        margin-bottom: -40px;

    }

    .card {
        width: 17rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s, box-shadow 0.3s;
        
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card-img-top {
        height: 100px; /* Réduction de la hauteur */
        object-fit: cover;
    }

    .card-body {
        padding: 8px; /* Réduction du padding */
    }

    .card-title {
        font-size: 1rem; /* Réduction de la taille de la police */
        font-weight: bold;
        color: #024d70;
    }

    .card-text {
        color: #555;
        font-size:0.75rem; /* Réduction de la taille de la description */
    }

    .pagination {
        justify-content: center;
    }
    /* Conteneur du formulaire */
.filter-form {
    display: flex;
    justify-content: flex-end; /* Alignement à droite */
    align-items: center; /* Alignement vertical */
    margin-bottom: 15px; /* Espace avec les éléments suivants */
    margin-top: 10px; /* Espace avec les éléments suivants */


}

/* Champs du formulaire */
.filter-form input[type="text"],
.filter-form input[type="date"] {
    width: 150px; /* Réduction de la largeur */
    margin-right: 10px; /* Espacement entre les champs */
    padding: 5px; /* Réduction du padding */
    font-size: 0.9rem; /* Réduction de la taille de police */
}

/* Bouton de filtrage */
.filter-form button {
    padding: 5px 15px; /* Ajustement du padding */
    font-size: 0.9rem; /* Réduction de la taille de police */
}
/* Réduire l'espacement entre les cartes */
#eventsList {
    display: flex;
    flex-wrap: wrap;
    gap: 5px; /* Espacement maximal de 5 pixels */
    justify-content: center;
}
</style>

</head>
<body>
    <div id="navbar-container"></div>

    <div class="container my-5">
        <header>
            <h1 class="mb-4">Explorer les Événements</h1>
        </header>

       <!-- Formulaire de filtrage -->
       <form method="GET" action="" class="filter-form">
        <input 
            type="text" 
            id="location" 
            name="location" 
            class="form-control" 
            placeholder="Lieu" 
            value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">

        <input 
            type="date" 
            id="date" 
            name="date" 
            class="form-control" 
            value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">

        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

        <!-- Affichage des événements -->
        <div class="row d-flex flex-wrap justify-content-center" id="eventsList">
            <?php if (!empty($events)): ?>
                <?php foreach ($events as $event): ?>
                    <div class="col-md-3 d-flex justify-content-center mb-3">

                    <div class="card">
                        <img src="../<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top img-fluid" alt="Événement">
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
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun événement trouvé.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="javascript:void(0)" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Gestion de l'inscription aux événements et de la pagination
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

        // Chargement des événements et pagination
        document.addEventListener('DOMContentLoaded', function () {
            function loadEvents(page) {
                fetch(`?page=${page}`)
                    .then(response => response.text())
                    .then(data => {
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(data, 'text/html');

                        // Mise à jour de la liste d'événements
                        const newEvents = newDoc.querySelectorAll('#eventsList .card');
                        const eventsList = document.getElementById('eventsList');
                        eventsList.innerHTML = ''; // Réinitialisation
                        newEvents.forEach(event => eventsList.appendChild(event));

                        // Mise à jour de la pagination
                        const newPagination = newDoc.querySelector('.pagination');
                        const pagination = document.querySelector('.pagination');
                        pagination.innerHTML = newPagination.innerHTML;
                        attachPaginationEvents(); // Réattacher les événements
                    })
                    .catch(error => console.error('Erreur lors du chargement des événements :', error));
            }

            function attachPaginationEvents() {
                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const page = this.getAttribute('data-page');
                        loadEvents(page); // Charger la page sélectionnée
                    });
                });
            }

            attachPaginationEvents(); // Initialiser les événements au chargement de la page
        });
    </script>
    
</body>
</html>
