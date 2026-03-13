<?php
    session_start();
    $erreur = ""; // On initialise une variable pour gérer les messages d'erreur

    if (isset($_POST["bout"])) {
        $pseudo = trim($_POST["pseudo"]);
        $mdp = $_POST["mdp"];
        
        $id = mysqli_connect("localhost", "root", "", "chatbox");
        if (!$id) {
            die("Erreur de connexion à la base de données.");
        }

        $req_check = "SELECT idu FROM users WHERE pseudo = ?";
        $stmt_check = mysqli_prepare($id, $req_check);
        mysqli_stmt_bind_param($stmt_check, "s", $pseudo);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check); // Nécessaire pour compter les lignes avec une requête préparée
     
        // Si le pseudo n'existe pas (0 ligne trouvée)
        if (mysqli_stmt_num_rows($stmt_check) == 0) {
            
            // Hachage du mot de passe
            $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
            
            // Requête préparée pour l'insertion
            $req_insert = "INSERT INTO users (pseudo, mdp) VALUES (?, ?)";
            $stmt_insert = mysqli_prepare($id, $req_insert);
            mysqli_stmt_bind_param($stmt_insert, "ss", $pseudo, $mdp_hash);
            
            if(mysqli_stmt_execute($stmt_insert)){
                mysqli_stmt_close($stmt_insert);
                mysqli_stmt_close($stmt_check);
                mysqli_close($id);
                // Redirection vers la connexion après succès
                header("location: connexion.php"); 
                exit;
            } else {
                $erreur = "<p style='color:red;'>Erreur lors de l'inscription.</p>";
            }
        } else {
            // Désinfection du pseudo pour éviter une faille XSS lors de l'affichage de l'erreur
            $pseudo_safe = htmlspecialchars($pseudo, ENT_QUOTES, 'UTF-8');
            $erreur = "<p style='color:red;'>Le pseudo <b>$pseudo_safe</b> est déjà utilisé.</p>";
        } 
        
        mysqli_stmt_close($stmt_check);
        mysqli_close($id);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="style_inscription.css">
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1>Inscription</h1>
            <h2>Inscrivez-vous et profitez de l’expérience Chatbox pour communiquer avec vos proches.</h2>
        </div>
        <form action="inscription.php" method="POST">
            <div class="pseudo-container">
                <p>Pseudo :</p>
                <input type="pseudo" name="pseudo" placeholder="Pseudo : " required>
            </div>
            <div class="mdp-container">
                <p>Mot de passe :</p>
                <input type="password" name="mdp" placeholder="Mot de passe : " required>
            </div>
            
            <?php if(!empty($erreur)) { echo $erreur; } ?>
            
            <input type="submit" value="S'inscrire" name="bout">
        </form>
        <div class="connexion-link-container">
            <p>Vous avez déjà un compte ?</p>
            <a href="connexion.php">Se connecter</a>
        </div>
    </div>
</body>
</html>