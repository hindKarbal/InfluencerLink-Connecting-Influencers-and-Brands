<?php  
//démarrer une session et établit une connexion à la base de données
session_start();
$bdd = new PDO("mysql:host=localhost;port=3306;dbname=dataproject", "root", "");

// vérifie si l'identifiant de l'influenceur est présent dans l'URL (via $_GET['id_inf']), et s'il n'est pas vide
if(isset($_GET['Username']) AND !empty($_GET['Username'])){
      //récupère les informations de l'influenceur correspondant à cet identifiant dans la base de données
      $getid= $_GET['Username'];
      $recupUser = $bdd->prepare('SELECT * FROM influencer WHERE Username = ?');
      $recupUser->execute(array($getid));
     // Si les informations sont récupérées avec succès (via $recupUser->rowCount() > 0)
    if ($recupUser->rowCount() > 0) { 
     //le code vérifie si le formulaire de message a été soumis (via isset($_POST['envoyer']))
     if (isset($_POST['envoyer'])) {
        if (!empty($_POST['message'])) {
        $message = htmlspecialchars($_POST['message']);
        //le message est inséré dans la base de données avec l'identifiant de l'influenceur et de la marque actuelle
        $insererMessage = $bdd->prepare('INSERT INTO message(message,destinataire,autour)VALUES(?,?,?)');
        $insererMessage->execute(array($message, $_GET['Username'], $_SESSION['user_name']));}
      }
      //un message d'erreur est affiché
    }else { echo "Aucun utilisateur trouve ";}

//un message d'erreur est affiché

}else { echo "Aucun identifiant trouve" ;}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!--définit l'encodage des caractères utilisé pour afficher la page, qui est UTF-8-->
        <meta charset="UTF-8">
        <!--la page doit être rendue dans la dernière version compatible d'Internet Explorer-->
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <!--les propriétés de l'affichage initial de la page sur les différents types d'appareils-->
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <!--d'ajouter des icônes à la page-->
       <script src="https://kit.fontawesome.com/687f59c35b.js" crossorigin="anonymous"></script>
       <link rel="stylesheet" href="message_inf.css">
        <title>Message</title>
        <!--  l'élément <link> qui définit l'icône de page-->
        <link rel="icon" href="images/l.gif" sizes="128x128" style="border-radius: 50%;">
</head>

<body>

     <div class="side-menu">
        <div class="lg">
         <a href="accueil.html"  > <img class="logo"src="images/logo.png"   alt="" > </li> </a>
        </div>
        <br/>
         <ul><!--une liste de liens de navigation.-->
            <a href="accueil.html"><li><i class="fa-solid fa-house" style="color: #ffffff;"></i><span class="titre ">Home</span></li></a>
            <a href="espace_br.php"><li><i class="fa-solid fa-user" style="color: #ffffff;"></i><span class="titre ">My Profile</span></li></a>
            <a  href="logout.php"><li><i class="fa-solid fa-key" style="color: #ffffff;"></i><span class="titre ">Log out</span></li></a>
         </ul>
     </div>
     <br/>
     <p style="color:#84abc2;"> Send Your Message to create change and make a positive impact </p>

     <br/>
   <div class="profile" style="margin-left:500px;">
      <form method="POST" action="" > <!-- un formulaire pour envoyer un message à un utilisateur de type "influencer" -->
        <!-- l'attribut action est vide, ce qui signifie que les données du formulaire seront envoyées vers la même page qui contient le formulaire  -->
        <textarea name="message"></textarea>
        <br/><br/> 
      <input type="submit" name="envoyer">
     </form>
    </div>
       <p style="color:#84abc2;"> Here is your conversation with this influencer </p>

<section id="message">
      <?php
      //récupère tous les messages échangés entre l'utilisateur connecté (l'id est stocké dans $_SESSION['id_br']) et un utilisateur sélectionné via l'URL ($_GET['id_inf'])
      $recupMessage = $bdd->prepare('SELECT * FROM message WHERE (autour = ? AND destinataire = ?) OR (autour = ? AND destinataire = ?)');
      $recupMessage->execute(array($_SESSION['user_name'], $_GET['Username'], $_GET['Username'], $_SESSION['user_name']));
    
      
      while ($message = $recupMessage->fetch()) {
         if ($message['destinataire'] == $_SESSION['user_name']) {
             // Afficher les messages reçus
             echo '<p style="color:red;">' . $message['message'] . ' <span style="color:#84abc2;">(Message received)</span></p>';
         } elseif ($message['destinataire'] == $_GET['Username']) {
             // Afficher les messages envoyés
             echo '<p style="color:green;">' . $message['message'] . ' <span style="color:#84abc2;">(Message sent)</span></p>';
         }
     }
    ?>
</section>
</body>
</html>
