<?php 
    require 'database.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST)){ #si pas vide
        $id = checkInput($_POST['id']); #cela veut dire que j'ai appuyer sur le bouton oui
        
        $db = Database::connect();
        $statement = $db->prepare("DELETE FROM items WHERE id = ?");#supprime moi de la table items ou l'id est egal a ce qu'on a donné
        $statement->execute(array($id)); 
        Database::disconnect();
        header("location: index.php"); #retourner sur la page index.php une fois un item supprimé
    }

    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
?>


<!DOCTYPE html>
<html>
    <head>
      <title>Burger Code</title>
      <meta charset="utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
      <link href='http://fonts.googleapis.com/css?family=Holtwood+One+SC' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
      <link rel="stylesheet" href="../style.css">
    </head>
    
    <body>
        <h1 class="text-logo"><span class="bi-shop"></span> Burger Code <span class="bi-shop"></span></h1>
        <div class="container admin">
            <div class="row">
 
                <h1><strong>Supprimer un item</strong></h1>  
                <br>
                <form class="form" role="form" action="delete.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <p class="alert alert-warning">Etes-vous sur de vouloir suprrimer ?</p>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-warning"> Oui</button>
                        <a class="btn btn-secondary" href="index.php"></span> Non</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>