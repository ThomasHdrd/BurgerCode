<?php 
    require 'database.php';

    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = ""; 

    if(!empty($_POST)){ #si le post n'est pas vide
        $name = checkInput($_POST['name']); #recuperer le chemin de name
        $description = checkInput($_POST['description']);
        $price = checkInput($_POST['price']);
        $category = checkInput($_POST['category']);
        $image = checkInput($_FILES['image']['name']);
        $imagePath = '../images' . basename($image); #recuperer le chemin de l'image
        $imageExtension = pathinfo($imagePath, PATHINFO_EXTENSION); #recuperer l extension de l'image
        $isSuccess = true; #si toutes les conditions on été réalisées
        $isUploadSuccess = false; #si tous les fichier ont été upload

        #si le champ est vide pour chaque catégorie
        if (empty($name)) {
            $nameError = 'Ce champ ne peut pas être vide'; #alors retourner ce message d'erreur
            $isSuccess = false;
        }

        if (empty($description)) {
            $descriptionError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if (empty($price)) {
            $priceError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if (empty($category)) {
            $categoryError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }

        if (empty($image)) {
            $imageError = 'Ce champ ne peut pas être vide';
            $isSuccess = false;
        }
        else { #s'il n'y a pas d'erreur
            $isUploadSuccess = true;  #alors upload success
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif" ) { #si extension est different de jpg, png etc...
                $imageError = "Les fichiers autorises sont: .jpg, .jpeg, .png, .gif"; #afficher le message suivant
                $isUploadSuccess = false; #probleme avec l image
            }
            if(file_exists($imagePath)) { #chemin de l'image existe deja ou pas
                $imageError = "Le fichier existe deja"; #afficher ce message
                $isUploadSuccess = false;
            }
            if($_FILES["image"]["size"] > 500000) { #si l'image est supérieur à 500Ko 
                $imageError = "Le fichier ne doit pas depasser les 500KB"; #alors afficher ce message
                $isUploadSuccess = false;
            }
            if($isUploadSuccess) { 
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) { #prendre une image et la mettre dans le vrai chemin entier + renvoyer si j'ai reussi ou pas
                    $imageError = "Il y a eu une erreur lors de l'upload"; #si pas reussi alors envoyer ce message
                    $isUploadSuccess = false;
                } 
            }
        } 

        #success sut tout le reste 
        if($isSuccess && $isUploadSuccess)
        {
            $db = Database::connect(); #ajouter a notre base de données
            $statement = $db->prepare("INSERT INTO items (name,description,price,category,image) values(?, ?, ?, ?, ?)"); #inserer dans la table le name, description ect...
            $statement->execute(array($name,$description,$price,$category,$image)); #lors de l'execution la remplir avec le nom de chaque element
            Database::disconnect();
            header("Location: index.php"); #voir le dernier element en haut de index.php et revenir sur cette page
        }
    }

    #declarer la fonction
    function checkInput($data){
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
 
                <h1><strong>Ajouter un item</strong></h1>  
                <br>
                <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nom" value="<?php echo $name; ?>">
                        <span class="help-inline"><?php echo $nameError; ?></span>
                    </div>

                    <div class="form-group">
                    <label for="description">Description:</label>
                        <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                        <span class="help-inline"><?php echo $descriptionError; ?></span>
                    </div>

                    <div class="form-group">
                    <label for="price">Prix: (en €)</label>
                        <input type="number" step="0.1" class="form-control" id="price" name="price" placeholder="Prix" value="<?php echo $price; ?>">
                        <span class="help-inline"><?php echo $priceError; ?></span>
                    </div>
                    

                    <div class="form-group">
                    <label for="category">Catégorie:</label>
                        <select class = "form-control" name="category" id="category">
                            <?php 
                                $db = Database::connect(); 
                                foreach($db->query('SELECT * FROM categories') as $row){
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $descriptionError; ?></span>
                    </div>

                    <div class="form-group">
                        <label for="image">Sélectionner une image</label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php echo $imageError;?></span>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="bi-pencil"></span> Ajouter</button>
                        <a class="btn btn-primary" href="index.php"><span class="bi-arrow-left"></span> Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>