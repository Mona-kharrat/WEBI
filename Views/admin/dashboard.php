<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #024d70;
            color: #fff;
            padding: 20px 10px;
            position: fixed;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #03688a;
        }

        .sidebar .active {
            background-color: #03688a;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .table th, .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <h4 class="text-center">Admin Panel</h4>
            <a href="#roles" class="active"><i class="fas fa-user-shield"></i> Gestion des Rôles</a>
            <a href="gestion_user.php"><i class="fas fa-users"></i> Gestion des Utilisateurs</a>
            <a href="gestion_event.php"><i class="fas fa-calendar-check"></i> Gestion des Événements</a>
        </div>

        <!-- Main Content -->
        <div class="content">
           
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
