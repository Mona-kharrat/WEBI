<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Plateforme de Gestion d'Événements</title>
  
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    html, body {
      height: 100%; 
      margin: 0; 
      display: flex; 
      flex-direction: column; 
    }
    
    .jumbotron {
      height: 95vh; 
      background: url('images/hero2.png') no-repeat center center;
      background-size: cover;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-shadow: 2px 2px 4px rgba(68, 68, 68, 0.76);
      position: relative;
    }

   
    .slogan {
      font-size: 1.5rem;
      font-weight: bold;
      display: inline-block;
      white-space: nowrap;
      overflow: hidden;
      border-right: 2px solid;
      width: 0;
      animation: typing 6s steps(55, end) infinite, blink 0.75s step-end infinite;
    }
   
   .service-card img {
    height: 200px;
    object-fit: cover;
  }
   
   footer {
    margin-top: auto; 
  }
    @keyframes typing {
      from { width: 0 }
      to { width: 60% }
    }
  
    @keyframes blink {
      from, to { border-color: transparent }
      50% { border-color: white }
    }

  </style>
</head>
<body>


<div id="navbar-container"></div>


<div class="jumbotron text-center">
    <h1 class="display-4" ><strong>Organisez vos événements en toute simplicité</strong></h1>
    <p class="lead slogan">Votre hub pour planifier, gérer et briller lors de vos événements.</p>

    <a href="Views\authentification\inscription.php" class="btn btn-primary btn-lg">Créer un compte</a>
</div>
  

<section class="container text-center my-5">
  <h2 class="mb-4 font-weight-bold" style="margin-top: 80px;">Bienvenue sur <span style="color: #007bff;">WEBI !</span></h2>
  <div class="row align-items-center">
      <div class="col-md-6">
          <img src="images/organize_events.jpg" class="img-fluid" style="max-width: 100%; height: auto;">
      </div>
      <div class="col-md-6">
          <p class="lead">
              Bienvenue sur WEBI !
              Notre plateforme vous permet de gérer vos événements (conférences, webinaires, ateliers...) avec facilité.
              Créez un compte, planifiez vos événements, et inscrivez-vous en quelques clics. 
              Notre plateforme intuitive rend l'organisation simple et efficace, tant pour les participants 
              que pour les administrateurs.
              Rejoignez-nous et transformez vos événements en succès !
              <a href="#" class="btn btn-primary btn-lg mt-3">Commencez dès maintenant</a>
            </section>
          </p>
      </div>
  </div>
</section>

 

<section id="services" class="container py-5">
  <div class="text-center mb-4">
    <h2>Nos Services</h2>
    <p>Découvrez ce que nous offrons pour la gestion de vos événements.</p>
  </div>
  <div class="row">
    <div class="col-md-4">
      <div class="card service-card">
        <img src="images/planification.jpg" class="card-img-top" alt="Planification">
        <div class="card-body">
          <h5 class="card-title">Planification d'Événements</h5>
          <p class="card-text">Planifiez facilement vos conférences, ateliers et webinaires grâce à notre plateforme intuitive.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card">
        <img src="images/inscri.jpg" class="card-img-top" alt="Inscription">
        <div class="card-body">
          <h5 class="card-title">Inscription aux Événements</h5>
          <p class="card-text">Inscrivez-vous rapidement aux événements qui vous intéressent et recevez des rappels automatiques.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card service-card">
        <img src="images\gestion.jpg" class="card-img-top" alt="Gestion">
        <div class="card-body">
          <h5 class="card-title">Gestion des événements</h5>
          <p class="card-text">Les administrateurs peuvent gérer les utilisateurs, suivre les inscriptions et optimiser les événements.</p>
        </div>
      </div>
    </div>
  </div>
</section>
</section>

<footer class="bg-dark text-white py-4">
  <div class="container text-center">
    <p>&copy; 2024 webi - Tous droits réservés.</p>
  </div>
</footer>
 
<script>
  fetch('navbar.html')
    .then(response => response.text())
    .then(data => {
      document.getElementById('navbar-container').innerHTML = data;
    })
    .catch(error => console.log('Erreur lors du chargement de la navbar:', error));
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
