<?php
session_start();

// Vérifier si la variable de session 'users' existe
if (isset($_SESSION['users'])) {
    $users = $_SESSION['users'];
} else {
    echo "Aucune donnée d'utilisateur trouvée dans la session.";
    $users = [];
}

if (empty($users)) {
    echo "Aucun utilisateur trouvé.";
} else {
    echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
            </tr>";
    foreach ($users as $user) {
        echo "<tr>
                <td>{$user['id']}</td>
                <td>{$user['username']}</td>
                <td>{$user['email']}</td>
              </tr>";
    }
    echo "</table>";
}
?>
