<?php
session_start();
$userId = $_SESSION['user']['id'];

    if (empty($userId)) {
        echo "ID utilisateur invalide.";
        return;
    }
    require_once '../../Models/EventModel.php';
    $eventModel = new EventModel();
    $events = $eventModel->getUserRegisteredEvents($userId);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Événements Inscrits</title>
    
</head>
<body>
    
    <header>
        <h1>Mes Événements Inscrits</h1>
    </header>
    <main>
        <?php if (!empty($events)) : ?>
            <ul>
                <?php foreach ($events as $event) : ?>
                    <li>
                        <h2><?= htmlspecialchars($event['title']) ?></h2>
                        <p><strong>Description :</strong> <?= htmlspecialchars($event['description']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
                        <p><strong>Lieu :</strong> <?= htmlspecialchars($event['location']) ?></p>
                        <p><strong>Catégorie :</strong> <?= htmlspecialchars($event['category']) ?></p>
                        <?php if (!empty($event['image'])) : ?>
                            <img src="<?= htmlspecialchars($event['image']) ?>" alt="Image de l'événement" style="max-width: 300px;">
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else : ?>
            <p>Vous n'êtes inscrit(e) à aucun événement.</p>
        <?php endif; ?>
    </main>
    
</body>
</html>
