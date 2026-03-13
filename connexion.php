<?php
    session_start();

    if (isset($_POST["bout"])){
        $pseudo = ucfirst(trim($_POST["pseudo"]));
        $mdp = $_POST["mdp"];
        
        $id = mysqli_connect("localhost", "root", "", "chatbox");
        if (!$id) {
            die("Erreur de connexion à la base de données.");
        }

        $requete = "SELECT pseudo, mdp FROM users WHERE pseudo = ?";
        $stmt = mysqli_prepare($id, $requete);
        mysqli_stmt_bind_param($stmt, "s", $pseudo);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if($ligne = mysqli_fetch_assoc($res)){
            
            if(password_verify($mdp, $ligne['mdp'])){
                // Succès : on ouvre la session
                $_SESSION["pseudo"] = $ligne['pseudo'];
                header("location: chat.php");
                exit;
            } else {
                $erreur = "<h3 style='color:red;'>Erreur de login ou de mot de passe.</h3>";
            }
        } else {

            $erreur = "<h3 style='color:red;'>Erreur de login ou de mot de passe.</h3>";
        }
        
        mysqli_stmt_close($stmt);
        mysqli_close($id);
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbox - Connexion</title>
    <link rel="stylesheet" href="style_connexion.css">
</head>
<body>
    <div class="container">
        <div class="title-container">
            <h1>Connexion</h1>
            <h2>Veuillez vous connecter pour accéder à Chatbox <br> et parler avec vos proches.</h2>
        </div>
        <form action="" method="POST">
            <div class="pseudo-container">
                <p>Pseudo :</p>
                <input type="pseudo" name="pseudo" placeholder="Pseudo : " required>
            </div>
            <div class="mdp-container">
                <p>Mot de passe :</p>
                <input type="password" name="mdp" placeholder="Mot de passe : " required>
            </div>
            <?php if(isset($erreur)) {echo $erreur;} ?>
            <input type="submit" value="Se connecter" name="bout">
        </form>
        <div class="signin-container">
            <p>Vous n'avez pas de compte ?</p>
            <a href="inscription.php"> S'inscrire</a>
        </div>
    </div>
</body>
</html>