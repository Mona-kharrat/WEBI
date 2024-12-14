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
        .stats-section {
            padding: 2rem;
            background-color: #e9ecef;
            margin: 2rem 0;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="sidebar">
        <h3>Menu</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">Gestion des Utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="gestion_event.php">Gestion des Événements</a>
            </li>
        </ul>
    </div>

    <div class="content">
        <header>
            <h1 class="mb-4">Gestion des utilisateurs</h1>
        </header>

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
            <h3>Statistiques</h3>
            <p>Total d'utilisateurs : <?php echo $totalUsers; ?></p>
            <p>Total d'événements : <?php echo $totalEvents; ?></p>
        </div>

        <!-- Tableau des utilisateurs -->
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>nom</th>
                <th>email</th>
                <th>role</th>
                <th>created_at</th>
                <th>action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td class="action-btns">
                            <button class="btn btn-danger btn-sm validate-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                                <i class="fas fa-ban"></i> 
                            </button>
                            <button class="btn btn-success btn-sm moderate-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                                <i class="fas fa-unlock"></i> 
                            </button>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                                <i class="fas fa-trash"></i> 
                            </button>
                            <button class="btn btn-warning btn-sm update-btn" data-bs-toggle="modal" data-bs-target="#editModal<?php echo htmlspecialchars($user['id']); ?>">
                                <i class="fas fa-pen"></i> 
                            </button>
                            <!-- Modal pour la modification -->
                            <div class="modal fade" id="editModal<?php echo htmlspecialchars($user['id']); ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo htmlspecialchars($user['id']); ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo htmlspecialchars($user['id']); ?>">Modifier le rôle</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="../../Controllers/personneController.php?action=update">
                                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">
                                                <div class="mb-3">
                                                    <label for="role<?php echo htmlspecialchars($user['id']); ?>" class="form-label">Rôle</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="role" id="role_user<?php echo htmlspecialchars($user['id']); ?>" value="user" <?php echo ($user['role'] == 'user') ? 'checked' : ''; ?> required>
                                                        <label class="form-check-label" for="role_user<?php echo htmlspecialchars($user['id']); ?>">User</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="role" id="role_admin<?php echo htmlspecialchars($user['id']); ?>" value="admin" <?php echo ($user['role'] == 'admin') ? 'checked' : ''; ?> required>
                                                        <label class="form-check-label" for="role_admin<?php echo htmlspecialchars($user['id']); ?>">Admin</label>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-success">Enregistrer les modifications</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Aucun utilisateur trouvé.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item<?php echo $page == $i ? ' active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if ($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
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
