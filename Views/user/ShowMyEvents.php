<?php
session_start();

// Vérifier si les événements existent dans la session
$events = isset($_SESSION['events']) ? $_SESSION['events'] : [];

// Déboguer: Afficher le contenu de $events
var_dump($events);  // Vérifie les événements avant de les afficher

if (!isset($_SESSION['user']['id'])) {
    header("Location: /webi/Views/Authentification.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes événements inscrits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <a href="AddEvent.php">Ajouter un événement</a>
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
                        <?php if (!empty($events)): ?>
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
</body>
</html>
