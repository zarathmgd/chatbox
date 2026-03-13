<?php
session_start();
if(!isset($_SESSION["pseudo"])){
    header("location:connexion.php");
    exit;
}

$id = mysqli_connect("localhost", "root", "", "chatbox");
if (!$id) {
    die("Erreur de connexion à la base de données.");
}

$id_message = $_GET['id'];
$pseudo_session = $_SESSION['pseudo'];

// 1. Utilisation d'une requête préparée contre les injections SQL
$requete = "DELETE FROM messages WHERE idm = ? AND pseudo = ?";
$stmt = mysqli_prepare($id, $requete);

// "is" signifie que l'on attend un Integer (idm) et un String (pseudo)
mysqli_stmt_bind_param($stmt, "is", $id_message, $pseudo_session);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($id);

// 2. Redirection stricte et sécurisée
header("Location: chat.php");
exit;
?>