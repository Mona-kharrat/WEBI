<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Moderne avec Masquage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Inclus Font Awesome -->
    <style>
        /* Global styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            height: 2000px; /* Juste pour permettre le défilement */
        }

        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background-color: rgba(2, 77, 112, 0.85); /* Blue background */
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 999;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        /* Navbar links container */
        .navbar-nav {
            display: flex; /* Utilisation de flex pour aligner les liens horizontalement */
            list-style-type: none; /* Enlever les puces des listes */
            padding: 0; /* Enlever le padding par défaut */
            margin: 0; /* Enlever la marge par défaut */
        }

        /* Navbar links */
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            font-weight: 0;
            transition: color 0.3s ease;
            position: relative;
            display: inline-block; /* Pour s'assurer que chaque lien est en ligne */
        }
        
        .navbar a::before {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background-color: white;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            transition: width 0.3s ease;
        }

        .navbar a:hover::before {
            width: 100%;
        }

        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgba(2, 77, 112, 0.85);
            min-width: 160px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown-item {
            color: white;
            text-decoration: none;
            padding: 12px 16px;
            position: relative;
            display: block;
            overflow: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .dropdown-item::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: -100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
        }

        .dropdown-item:hover::before {
            left: 0;
        }

        /* Media query for responsiveness */
        @media (max-width: 768px) {
            .navbar {
                padding: 10px 30px;
            }

            .navbar a {
                margin-right: 10px;
                font-size: 14px;
            }
        }

        /* Hidden class for navbar */
        .navbar.hidden {
            transform: translateY(-100%);
        }
        
        /* Scrolled class for navbar */
        .navbar.scrolled {
            background-color: rgba(0, 100, 200, 0.85); /* Changer la couleur au défilement */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <a class="navbar-brand"><i class="fas fa-home"></i> WEBI</a>
    <div class="dropdown">
        <a class="nav-link" href="#"><i class="fas fa-tools"></i> Gestion</a>
        <div class="dropdown-content">
            <a class="dropdown-item" href="AddEvent.php"><i class="fas fa-calendar-plus"></i> Ajouter Évènement</a>
            <a class="dropdown-item" href="ShowMyEvents.php"><i class="fas fa-calendar-check"></i> Mes Évènements</a>
            <a class="dropdown-item" href="ShowAllEvents.php"><i class="fas fa-calendar-alt"></i> Autres Évènements</a>
        </div>
    </div>
    <div class="dropdown">
        <a class="nav-link" href="#"><i class="fas fa-cog"></i> Paramètres</a>
        <div class="dropdown-content">
            <a class="dropdown-item" href="views/authentification/Authentification.php"><i class="fas fa-sign-out-alt"></i> Se connecter</a>
            <a class="dropdown-item" href="{{ path('forgot-pass') }}"><i class="fas fa-key"></i> Mot de passe oublié</a>
            <a class="dropdown-item" href="{{ path('app_profil') }}"><i class="fas fa-user"></i> Voir mon profil</a>
        </div>
    </div>
</nav>

<!-- JavaScript to hide/show navbar on scroll -->
<script>
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar');

    // Detect scroll direction and hide/show navbar
    window.addEventListener('scroll', function() {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

        // Scroll down: hide navbar
        if (scrollTop > lastScrollTop) {
            navbar.classList.add('hidden');
        } else {
            navbar.classList.remove('hidden');
        }

        // Update last scroll position
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;

        // Optional: Change background when scrolling
        if (scrollTop > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

</body>
</html>
