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

// Tri des événements par date la plus récente
usort($events, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Événements Inscrits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f8f9fa;
        }

        header {
            color: rgba(2, 77, 112, 0.85);
            padding: 2rem;
            text-align: center;
            position: relative;
            font-weight: bold;
            margin-top: 60px;
        }

        .card {
            border-radius: 15px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: #ffffff;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .card-img-top {
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 1.5rem;
            text-align: left;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #025074;
        }

        .card-text {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .card p {
            margin: 0;
            font-size: 0.9rem;
            color: #777;
        }

        .card p strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div id="navbar-container"></div>

    <header>
        <h1>Mes Événements Inscrits</h1>
    </header>
    <main class="container mt-5">
        <?php if (!empty($events)) : ?>
            <div class="row">
                <?php foreach ($events as $event) : ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?= htmlspecialchars($event['image']) ?>" class="card-img-top" alt="Image de l'événement">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($event['title']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                                <p><strong>Date :</strong> <?= htmlspecialchars($event['date']) ?></p>
                                <p><strong>Lieu :</strong> <?= htmlspecialchars($event['location']) ?></p>
                                <p><strong>Catégorie :</strong> <?= htmlspecialchars($event['category']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p>Aucun événement trouvé.</p>
        <?php endif; ?>
    </main>

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
