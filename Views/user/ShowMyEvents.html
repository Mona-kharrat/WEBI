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
            font-family: 'Poppins', sans-serif;
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            padding: 20px; 
        }

        h2 {
            color: #007bff; /* Couleur du titre */
        }

        .card {
            border: 1px solid #007bff; /* Bordure de la carte */
            border-radius: 10px; /* Coins arrondis */
            transition: transform 0.3s; /* Transition pour l'effet au survol */
            margin-top: 100px; /* Marge au-dessus de la carte */
            display: flex;
            justify-content: center;
            height: 100%;
        }

        .card:hover {
            transform: scale(1.05); /* Effet de zoom au survol */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Ombre au survol */
        }

        footer {
            background-color: #343a40; /* Couleur de fond du footer */
            color: white; /* Couleur du texte du footer */
            padding: 20px 0; /* Espacement intérieur */
            text-align: center; /* Centrer le texte du footer */
            margin-top: auto; /* Pousse le footer vers le bas */
        }
        
          
          
    </style>
</head>
<body>
    <div id="navbar-container"></div> 

    <div class="container">
        <div class="card w-75 mx-auto"> <!-- Carte pour afficher les événements -->
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
                        <tr>
                            <td colspan="4" class="text-center">Aucun événement trouvé.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2024 - Gestion d'événements</p>
    </footer>

    <script>
        function displayRegisteredEvents() {
            const registeredEventsList = document.getElementById('registered-events');
            let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents')) || [];

            // Vider la liste avant de réafficher les événements
            registeredEventsList.innerHTML = '';

            registeredEvents.forEach((event, index) => {
                const eventRow = document.createElement('tr');

                eventRow.innerHTML = `
                    <td>${event.title}</td>
                    <td>${event.date}</td>
                    <td>${event.location}</td>
                    <td><button class="btn btn-danger btn-sm delete-btn" data-index="${index}">Supprimer</button></td>
                `;

                registeredEventsList.appendChild(eventRow);
            });

            // Si aucun événement n'est trouvé, affichez un message dans le tableau
            if (registeredEvents.length === 0) {
                registeredEventsList.innerHTML = `<tr><td colspan="4" class="text-center">Aucun événement trouvé.</td></tr>`;
            }

            // Ajouter un événement de suppression à chaque bouton "Supprimer"
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const eventIndex = this.getAttribute('data-index');
                    removeEvent(eventIndex);
                });
            });
        }

        // Fonction pour supprimer un événement à partir du localStorage
        function removeEvent(index) {
            let registeredEvents = JSON.parse(localStorage.getItem('registeredEvents')) || [];
            registeredEvents.splice(index, 1);  // Supprimer l'événement à l'index spécifié
            localStorage.setItem('registeredEvents', JSON.stringify(registeredEvents));  // Mettre à jour le localStorage
            displayRegisteredEvents();  // Rafraîchir l'affichage des événements
        }

        window.onload = displayRegisteredEvents;
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
