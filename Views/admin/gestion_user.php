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
    margin-top: 2rem; /* Ajoute un espacement au-dessus de la pagination */
    justify-content: center; /* Centre horizontalement les éléments */
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

        .sidebar a {
            display: block;
            color: #fff;
            padding: 10px 15px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
            text-decoration: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        header {
            color: rgba(2, 77, 112, 0.85);
            padding: 2rem;
            text-align: center;
            font-weight: bold;
        }

       
        .content {
            margin-left: 270px;
        }
      .table {
    float: center;

}


    </style>
</head>
<body>
<div class="container my-5">
    <div class="navbar-container">
        <h3>Menu</h3>
        <ul class="nav flex-column">
           
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">Gestion des Événements</a>
            </li>
        </ul>
    </div>
    </div>

<div class="container my-5">
    <header>
        <h1 class="mb-4">Gestion des users</h1>
    </header>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>nom</th>
            <th>email</th>
            <th>role</th>
            <th>status</th>
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
                    <td>
                        <?php echo ($user['status'] == 1) ? 'Bloqué' : 'Actif'; ?>
                    </td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td class="action-btns">
                        <button class="btn btn-danger btn-sm validate-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                        <i class="fas fa-ban"></i> 
                        </button>
                        <button  class="btn btn-success btn-sm moderate-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
                        <i class="fas fa-unlock"></i> 
                        </button>
                        <button class="btn btn-primary btn-sm delete-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
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
            
            <!-- Boutons radio pour User et Admin -->
            <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="role_user<?php echo htmlspecialchars($user['id']); ?>" value="user" <?php echo ($user['role'] == 'user') ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="role_user<?php echo htmlspecialchars($user['id']); ?>">
                    User
                </label>
            </div>
            
            <div class="form-check">
                <input class="form-check-input" type="radio" name="role" id="role_admin<?php echo htmlspecialchars($user['id']); ?>" value="admin" <?php echo ($user['role'] == 'admin') ? 'checked' : ''; ?> required>
                <label class="form-check-label" for="role_admin<?php echo htmlspecialchars($user['id']); ?>">
                    Admin
                </label>
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
                <td colspan="5" class="text-center">Aucun user trouvé.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <nav style="margin-top: 2rem;">
    <ul class="pagination justify-content-center">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Gestion du bouton de blocage (validate-btn)
    document.querySelectorAll('.validate-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            if (confirm('Voulez-vous bloquer cet utilisateur ?')) {
                fetch(`../../Controllers/personneController.php?action=blockUser&id=${userId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Utilisateur bloqué avec succès.');
                        location.reload();  // Recharger la page pour voir les modifications
                    } else {
                        alert('Erreur lors du blocage de l\'utilisateur.');
                    }
                });
            }
        });
    });

    // Gestion du bouton de déblocage (moderate-btn)
    document.querySelectorAll('.moderate-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            if (confirm('Voulez-vous débloquer cet utilisateur ?')) {
                fetch(`../../Controllers/personneController.php?action=unblockUser&id=${userId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Utilisateur débloqué avec succès.');
                        location.reload();  
                    } else {
                        alert('Erreur lors du déblocage de l\'utilisateur.');
                    }
                });
            }
        });
    });

    // Gestion du bouton de suppression (delete-btn)
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            if (confirm('Voulez-vous supprimer cet utilisateur ?')) {
                fetch(`../../Controllers/personneController.php?action=deleteUser&id=${userId}`, {
                    method: 'GET'
                })
                .then(response => {
                    console.log(response); // Afficher la réponse complète du serveur
                    return response.json(); // Tenter de convertir la réponse en JSON
                })
                .then(data => {
                    if (data.success) {
                        alert('Utilisateur supprimé avec succès.');
                        // Supprimer la ligne de la table sans recharger la page
                        this.closest('tr').remove();
                    } else {
                        alert('Erreur lors de la suppression de l\'utilisateur.');
                    }
                });
            }
        });
    });
});

</script>

</body>
</html>