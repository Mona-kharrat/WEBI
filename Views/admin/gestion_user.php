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
    <title>Gestion des Utilisateurs</title>
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
    </style>
</head>
<body>
<div class="container my-5">
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
                        <button  class="btn btn-success btn-sm moderate-btn" data-id="<?php echo htmlspecialchars($user['id']); ?>">
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
                                                <input type="text" class="form-control" id="role<?php echo htmlspecialchars($user['id']); ?>" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" required>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            if (confirm('Voulez-vous supprimer cet user ?')) {
                window.location.href = `../../Controllers/userController.php?action=deleteAdmin&id=${userId}`;
            }
        });
    });
</script>

</body>
</html>
