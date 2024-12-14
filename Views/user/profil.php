<?php
session_start();

require_once '../../Models/personneModel.php';

$personneModel = new personneModel();
// Récupérer les données utilisateur
$user = $personneModel->getUserById($_SESSION['user']['id']); // Assurez-vous que cette méthode existe

$nbEventsCreated = $personneModel->getEventCountByUser($_SESSION['user']['id']);
$nbEventsRegistered = $personneModel->getEventRegistrationsCount($_SESSION['user']['id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>
    <!-- Inclure Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Inclure Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        <style>
    body {
        font-family: 'Arial', sans-serif;
        color: #333;
        background-color: #f8f9fa;
        font-size: 14px; /* Réduction de la taille de police générale */
    }

    header {
        color: rgba(2, 77, 112, 0.85);
        padding: 1rem; /* Réduction de l'espacement */
        text-align: center;
        position: relative;
        font-weight: bold;
        margin-top: 40px; /* Réduction de l'écart au sommet */
        font-size: 1.5rem; /* Réduction de la taille de police */
    }

    .card {
        padding: 1rem; /* Réduction de l'espacement interne */
        font-size: 0.9rem; /* Réduction de la taille de texte */
    }

    .card h4 {
        font-size: 1.2rem; /* Taille de titre réduite */
        margin-bottom: 0.8rem; /* Espacement réduit */
    }

    .btn {
        padding: 0.4rem 0.8rem; /* Boutons plus petits */
        font-size: 0.9rem; /* Texte plus petit pour les boutons */
    }

    .container {
        max-width: 800px; /* Réduction de la largeur pour une mise en page plus compacte */
    }

    .mb-3 label {
        font-size: 0.9rem; /* Réduction de la taille de texte des étiquettes */
    }

    .form-control {
        font-size: 0.85rem; /* Réduction de la taille de texte des champs */
        padding: 0.3rem; /* Réduction de l'espacement interne des champs */
    }

    canvas {
        max-height: 200px; /* Limitation de la hauteur du graphique */
    }
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
            margin-top: 50px;
        }


</style>

    
</head>
<body class="bg-light">
<div id="navbar-container"></div>

    <div class="container my-5">
       <header> 
        <h1 >Mon Profil</h1>
        </header> 

    <div class="row">
                <!-- Formulaire de mise à jour -->

        <div class="col-md-7">
                    <div class="card shadow-sm mb-7">
                        <div class="card-body">
                            <h4><i class="fas fa-edit me-2"></i> Mettre à jour mes informations</h4>
                            <form action="../../Controllers/personneController.php?action=updateProfile" method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nom d'utilisateur:</label>
                                    <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Mettre à jour</button>
                            </form>
                        </div>
                    </div>
                </div>
            <!--graphiques-->
                <div class="col-md-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h4><i class="fas fa-chart-bar me-2"></i> Statistiques des événements</h4>
                            <canvas id="eventsChart"></canvas>
                        </div>
                    </div>
                </div>
    </div>
    </div>
    </div>

    <!-- Script pour Chart.js -->
    <script>
        const ctx = document.getElementById('eventsChart').getContext('2d');
        const eventsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Événements créés', 'Événements inscrits'],
                datasets: [{
                    data: [<?= $nbEventsCreated ?>, <?= $nbEventsRegistered ?>],
                    backgroundColor: ['#007bff', '#28a745'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
   <script>
        fetch('navbar_user.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar-container').innerHTML = data;
            })
            .catch(error => console.log('Erreur lors du chargement de la navbar:', error));

    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
