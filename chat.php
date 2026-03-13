<?php
    session_start();
    
    if(!isset($_SESSION["pseudo"])){
        header("location:connexion.php");
        exit; 
    }

    $pseudo_get = $_SESSION["pseudo"];

    // Connexion à la BDD
    $id = mysqli_connect("localhost","root","","chatbox");
    if (!$id) {
        die("Erreur de connexion à la base de données.");
    }

    if(isset($_POST["bout"])){
        $message = trim($_POST["message"]);
        $destinataire = ucfirst(trim($_POST["destinataire"]));
        
        $requete = "INSERT INTO messages (pseudo, message, date, destinataire) VALUES (?, ?, NOW(), ?)";
        $stmt = mysqli_prepare($id, $requete);
        
        // Le "sss" signifie qu'on injecte 3 chaînes de caractères (strings)
        mysqli_stmt_bind_param($stmt, "sss", $pseudo_get, $message, $destinataire);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        // Redirection propre sans passer le pseudo dans l'URL
        header("location:chat.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_chat.css">
    <title>Chatbox</title>
</head>
<body>
     
    <a href="deconnexion.php">Se déconnecter</a>
    <div class="container">
        <h1>Chatbox</h1>
        <div class="messages">
            <ul>
                <?php
                    // Requête préparée pour la lecture des messages
                    $requete = "SELECT * FROM messages WHERE pseudo = ? OR destinataire = ? OR destinataire = 'General' ORDER BY date DESC";
                    $stmt = mysqli_prepare($id, $requete);
                    mysqli_stmt_bind_param($stmt, "ss", $pseudo_get, $pseudo_get);
                    mysqli_stmt_execute($stmt);
                    $resultat = mysqli_stmt_get_result($stmt);

                    while($ligne = mysqli_fetch_assoc($resultat)){
                        
                        $destinataire = htmlspecialchars($ligne['destinataire'], ENT_QUOTES, 'UTF-8');
                        $pseudo = htmlspecialchars($ligne['pseudo'], ENT_QUOTES, 'UTF-8');
                        $message = htmlspecialchars($ligne['message'], ENT_QUOTES, 'UTF-8');
                        $date = htmlspecialchars($ligne['date'], ENT_QUOTES, 'UTF-8');
                        $id_msg = $ligne['idm']; // L'ID n'est pas affiché, on le garde tel quel
                 
                        if($pseudo == $pseudo_get){
                         echo "<li class='message-container sender'>
                                    <div class='pseudo'>$pseudo - ($destinataire)</div>
                                    <div class='message'>$message</div>
                                    <div class='date'>$date</div>
                                    <a class='delete-link' href='supprimer.php?id=$id_msg' style='color:red; font-size:0.8rem;' onclick='return confirm(\"Supprimer ce message ?\")'>Supprimer</a>
                                </li>";    
                        } else {
                         echo "<li class='message-container receiver'>
                                    <div class='pseudo'>$pseudo - ($destinataire)</div>
                                    <div class='message'>$message</div>
                                    <div class='date'>$date</div>
                                </li>";
                        }
                    }
                    mysqli_stmt_close($stmt);
                ?>
            </ul>
        </div>
        <div class="formulaire">
            <form action="chat.php" method="POST">      
                <input type="text" class="btn input-message" name="message" placeholder="Message :" required><br>
                <div class="send-receiver-container">
                    <select name="destinataire" required>
                        <option value="" selected disabled>Choissisez un destinataire : </option>
                        <option value="general">General</option>
                        <?php
                            // Requête préparée pour la liste des destinataires
                            $requete_users = "SELECT pseudo FROM users WHERE pseudo != ? AND pseudo != 'General'";
                            $stmt_users = mysqli_prepare($id, $requete_users);
                            mysqli_stmt_bind_param($stmt_users, "s", $pseudo_get);
                            mysqli_stmt_execute($stmt_users);
                            $res = mysqli_stmt_get_result($stmt_users);

                            while($ligne = mysqli_fetch_assoc($res)){
                                // Désinfection de la variable injectée dans l'attribut HTML "value"
                                $destinataire_opt = htmlspecialchars($ligne["pseudo"], ENT_QUOTES, 'UTF-8');
                                echo "<option value='$destinataire_opt'>$destinataire_opt</option>";
                            }
                            mysqli_stmt_close($stmt_users);
                            mysqli_close($id); // Fermeture propre de la BDD à la fin
                        ?>
                    </select>
                    <input type="submit" class="btn input-send" name="bout" value="Envoyer">
                </div>
            </form>
        </div>
    </div> 
</body>
</html>