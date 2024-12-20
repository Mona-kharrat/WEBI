<?php 
session_start();
require_once '../../Models/personneModel.php';

$personneModel = new personneModel();

// Configuration de la pagination
$limit = 6;  // Nombre d'utilisateurs par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Récupération des utilisateurs paginés
$users = $personneModel->getUsers($limit, $offset);
$totalUsers = $personneModel->getTotalUsersCount();
$totalPages = ceil($totalUsers / $limit);

// Définir le modèle pour les événements
require_once '../../Models/EventModel.php';
$eventModel = new EventModel();
$events = $eventModel->getAllEvents($page, $limit);
$totalEvents = $eventModel->getTotalEvents();
$totalEventPages = ceil($totalEvents / $limit);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs & Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        header {
            color: rgba(2, 77, 112, 0.85);
            padding: 2rem;
            text-align: center;
            font-weight: bold;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background-color: #f8f9fa;
            padding: 1rem;
        }
        .content {
            margin-left: 270px;
        }
        table {
            margin-top: 2rem;
        }
        .action-btns button {
            margin-right: 5px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>
<div class="container my-5">
    <div class="sidebar">
        <h3>Menu</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="gestion_user.php">Gestion des Utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#event">Gestion des Événements</a>
            </li>
        </ul>
    </div>

    <div class="content">
   
        <!-- Affichage des messages d'erreur -->
        <?php if (isset($_SESSION['user_errors']) && !empty($_SESSION['user_errors'])): ?>
            <div class="alert alert-danger">
                <?php foreach ($_SESSION['user_errors'] as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
                <?php unset($_SESSION['user_errors']); ?>
            </div>
        <?php endif; ?>

       <!-- Section des statistiques -->
       <div class="stats-section">
            <h3><i class="fas fa-chart-bar"></i> Statistiques</h3>
            <p><i class="fas fa-user"></i> Total d'utilisateurs : <?php echo $totalUsers; ?></p>
            <p><i class="fas fa-calendar"></i> Total d'événements : <?php echo $totalEvents; ?></p>
        </div>


      
<section id="event">
<?php


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
    <title>Gestion des Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        header {
            color: rgba(2, 77, 112, 0.85);
            padding: 2rem;
            text-align: center;
            font-weight: bold;
        }
        table {
            margin-top: 2rem;
        }
        .action-btns button {
            margin-right: 5px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #343a40;
            padding-top: 20px;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <header>
        <h1 class="mb-4">Gestion des Événements</h1>
    </header>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Titre</th>
            <th>Description</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo htmlspecialchars($event['id']); ?></td>
                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                    <td><?php echo htmlspecialchars($event['description']); ?></td>
                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                    <td class="action-btns">
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($event['id']); ?>">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" class="text-center">Aucun événement trouvé.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const eventId = this.getAttribute('data-id');
            if (confirm('Voulez-vous supprimer cet événement ?')) {
                window.location.href = `../../Controllers/EventController.php?action=deleteAdmin&id=${eventId}`;
            }
        });
    });
</script>
</body>
</html>

            </section>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion du bouton de blocage, déblocage, suppression
        document.querySelectorAll('.validate-btn, .moderate-btn, .delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.getAttribute('data-id');
                let action = '';
                if (this.classList.contains('validate-btn')) action = 'blockUser';
                else if (this.classList.contains('moderate-btn')) action = 'unblockUser';
                else if (this.classList.contains('delete-btn')) action = 'deleteUser';

                if (confirm(`Voulez-vous ${action.replace('User', ' cet utilisateur')} ?`)) {
                    fetch(`../../Controllers/personneController.php?action=${action}&id=${userId}`, {
                        method: 'GET'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(`Utilisateur ${action.replace('User', '')} avec succès.`);
                            location.reload();
                        } else {
                            alert(`Erreur lors du ${action.replace('User', '')} de l'utilisateur.`);
                        }
                    })
                }
            });
        });
    });
</script>

</body>
</html>
