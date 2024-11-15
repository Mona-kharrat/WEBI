<?php
session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: /webi/Views/login.php");
    exit();
}

echo "ID utilisateur connecté : " . $_SESSION['user']['id'];
$events = $_SESSION['events'] ?? [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements inscrits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; 
        }
        .card {
            border: 1px solid #007bff;
            border-radius: 10px; 
            transition: transform 0.3s; 
            margin-top: 100px; 
            display: flex;
            justify-content: center;
            height: 100%;
        }
        .card:hover {
            transform: scale(1.05); 
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); 
        }
        footer {
            background-color: #343a40; 
            color: white; 
            padding: 20px 0; 
            text-align: center; 
            margin-top: auto; 
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card w-75 mx-auto">
            <div class="card-body">
                <h2>Mes événements inscrits</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="registered-events">
                        <?php if (count($events) > 0): ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($event['title']); ?></td>
                                    <td><?php echo htmlspecialchars($event['date']); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td>
                                        <a href="deleteEvent.php?id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">Aucun événement trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2024 - Gestion d'événements</p>
    </footer>
</body>
</html>
