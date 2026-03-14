<?php
session_start();

// CYBERSÉCURITÉ : Si l'utilisateur n'est pas connecté OU n'est pas admin, on le dégage
if(!isset($_SESSION["pseudo"]) || $_SESSION["role"] !== 'admin'){
    header("location: chat.php");
    exit;
}

$id_db = mysqli_connect("localhost", "root", "", "chatbox");

// GESTION DE LA SUPPRESSION (Si un ID est passé dans l'URL)
if(isset($_GET['delete_id'])){
    $id_to_delete = $_GET['delete_id'];
    
    // 1. Récupérer le pseudo de l'utilisateur à supprimer (pour effacer ses messages)
    $req_pseudo = "SELECT pseudo FROM users WHERE idu = ?";
    $stmt_p = mysqli_prepare($id_db, $req_pseudo);
    mysqli_stmt_bind_param($stmt_p, "i", $id_to_delete);
    mysqli_stmt_execute($stmt_p);
    $res_p = mysqli_stmt_get_result($stmt_p);
    
    if($user_to_delete = mysqli_fetch_assoc($res_p)){
        $pseudo_cible = $user_to_delete['pseudo'];
        
        // On empêche l'admin de se supprimer lui-même
        if($pseudo_cible !== $_SESSION['pseudo']){
            
            // 2. Supprimer les messages de cet utilisateur (Expédiés ou reçus)
            // Note : Ton trigger 'archivage_message' va s'activer automatiquement !
            $req_del_msg = "DELETE FROM messages WHERE pseudo = ? OR destinataire = ?";
            $stmt_msg = mysqli_prepare($id_db, $req_del_msg);
            mysqli_stmt_bind_param($stmt_msg, "ss", $pseudo_cible, $pseudo_cible);
            mysqli_stmt_execute($stmt_msg);
            
            // 3. Supprimer l'utilisateur
            $req_del_user = "DELETE FROM users WHERE idu = ?";
            $stmt_user = mysqli_prepare($id_db, $req_del_user);
            mysqli_stmt_bind_param($stmt_user, "i", $id_to_delete);
            mysqli_stmt_execute($stmt_user);
        }
    }
    header("location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrateur</title>
    <link rel="stylesheet" href="style_chat.css">
</head>
<body>
    <a href="chat.php" class="return-link">Retour au chat</a>
    <div class="container">
        <h1>Gestion des Utilisateurs</h1>
        <ul>
            <?php
            // Affichage de la liste des utilisateurs
            $requete = "SELECT idu, pseudo, role FROM users ORDER BY role, pseudo";
            $resultat = mysqli_query($id_db, $requete);
            
            while($ligne = mysqli_fetch_assoc($resultat)){
                $idu = $ligne['idu'];
                $pseudo = htmlspecialchars($ligne['pseudo'], ENT_QUOTES, 'UTF-8');
                $role = htmlspecialchars($ligne['role'], ENT_QUOTES, 'UTF-8');
                
                echo "<li style='margin-bottom: 10px;'>";
                echo "<strong>[$role]</strong> $pseudo ";
                
                // Le bouton de suppression (sauf pour soi-même)
                if($pseudo !== $_SESSION['pseudo']){
                    echo "<a href='admin.php?delete_id=$idu' style='color: red; margin-left: 10px;' onclick='return confirm(\"Supprimer $pseudo et tous ses messages ?\")'>Supprimer</a>";
                }
                echo "</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>