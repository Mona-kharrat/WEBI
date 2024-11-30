<!-- ShowAllEvents.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorer les Événements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div id="navbar-container"></div> 
    <div class="container my-5">
        <h2 class="mb-4">Explorer les Événements</h2>
        <div class="row" id="eventsList">
            <?php
            // Vérification que les événements existent et affichage
            if (!empty($events)) {
                foreach ($events as $event) {
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <!-- Affichage de l'image si elle existe -->
                            <img src="../<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="Événement">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($event['date']); ?><br>
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                                <button class="btn btn-outline-primary btn-sm register-btn" data-title="<?php echo htmlspecialchars($event['title']); ?>" data-date="<?php echo htmlspecialchars($event['date']); ?>" data-location="<?php echo htmlspecialchars($event['location']); ?>">
                                    <i class="fas fa-user-plus"></i> S'inscrire
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>Aucun événement trouvé.</p>";
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
    <script>
        fetch('navbar_user.html')
            .then(response => response.text())
            .then(data => {
              document.getElementById('navbar-container').innerHTML = data;
            })
            .catch(error => console.log('Erreur lors du chargement de la navbar:', error));
    </script>
</body>
</html>
