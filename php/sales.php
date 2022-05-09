<?php
if(!isset($_SESSION['id']))
    header('location: ?i=login');

// Devra séparer sales et salesRequest

if(isset($_GET['saleID'])){
    $getProductInfos = $bdd->prepare('SELECT name, marketPosition, description, conditionP, category FROM products WHERE id = ? AND sellerID = ? AND saleStatus = 0'); // Récupère et vérifie que le produit est bien mis en vente par l'utilisateur
    $getProductInfos->execute(array($_GET['saleID'],
                                    $_SESSION['id']));

    $sale = $getProductInfos->fetch(PDO::FETCH_ASSOC);

    if(!empty($sale)){
        if(isset($_POST['nom_produit'], $_POST['description_produit'], $_POST['etat_produit'], $_POST['marketPosition'], $_POST['categorie_produit'], $_POST['idOldPictures'], $_FILES['pictures'])){
            require 'php/modules/hcaptcha.php';

            if(!hcaptcha($_POST['h-captcha-response']))
                mis_log("Captcha invalide !");
            else if(strlen(utf8_decode($_POST['description_produit']) > 300))
                mis_log("Description trop large.");
            else if($_POST['marketPosition'] < 1 || $_POST['marketPosition'] > 100) // Le prix ne peut être en dehors de cet intervalle
                mis_log("L'intervalle du prix est incorrect !");
            else
            {
                require 'php/modules/new_extern_product.php';

                // Mise à jour de la plupart des infos
                $req = $bdd->prepare('UPDATE products SET name = ?, marketPosition = ?, description = ?, conditionP = ?, category = ? WHERE id = ? AND sellerID = ?');
                $req->execute(array($_POST['nom_produit'],
                                    $_POST['marketPosition'],
                                    $_POST['description_produit'],
                                    $_POST['etat_produit'],
                                    $_POST['categorie_produit'],
                                    $_GET['saleID'],
                                    $_SESSION['id'])
                );

                $time = time();

                // Mise à jour des images
                $mainPictureID = $bdd->prepare('SELECT id FROM pictures WHERE productID = ? LIMIT 1'); // Récupère l'id de l'image principale
                $mainPictureID->execute(array($_GET['saleID']));

                $mainPictureID = $mainPictureID->fetch(PDO::FETCH_COLUMN);

                $deletePictureFromServer = $bdd->prepare('SELECT fileName FROM pictures WHERE id = ? AND productID = ?');
                $deletePictureFromBDD = $bdd->prepare('DELETE FROM pictures WHERE id = ? AND productID = ?');
                $numberOfPictures = $bdd->prepare('SELECT COUNT(id) FROM pictures WHERE productID = ?');
                for($i = 0; $i < 4; $i++)
                {
                    $fileName;

                    if(!empty($_POST['idOldPictures'][$i]) && $_POST['idOldPictures'][0] != $mainPictureID){ // Empêche de supprimer l'image principale
                        $deletePictureFromServer->execute(array($_POST['idOldPictures'][$i],
                                                                $_GET['saleID']));

                        $fileName = $deletePictureFromServer->fetch(PDO::FETCH_COLUMN);

                        if(!empty($fileName)){
                            unlink('images/products/' . $fileName); // On supprime l'ancienne image physiquement

                            $deletePictureFromBDD->execute(array($_POST['idOldPictures'][$i], // On supprime l'ancienne image de la bdd
                                                             $_GET['saleID']));
                        }
                    }

                    if(empty($_FILES['pictures']['name'][$i])) // S'il n'a pas été modifié on passe
                        continue;
                    else if(!getimagesize($_FILES['pictures']['tmp_name'][$i]))
                        echo '<p>Attention : Image ' . ($i + 1) .', le fichier envoyé n\'est pas une image !</p>';
                    else if($_FILES['pictures']['size'][$i] > 16777216) // Inférieur à 16 Mo
                        echo '<p>Attention : Image ' . ($i + 1) .', fichier trop lourd, taille maximum 16 Mo.</p>';
                    else{
                        $uploadFile = substr($_GET['saleID'] . "/" . $time . basename($_FILES['pictures']['name'][$i]), 0, 48);

                        move_uploaded_file($_FILES['pictures']['tmp_name'][$i], "images/products/" . $uploadFile);

                        var_dump($_POST['idOldPictures']);

                        // Permet de s'assurer que l'utilisateur ne contourne pas la limite de 4 images
                        if(!empty($_POST['idOldPictures'][$i])){
                            if(!empty($fileName)){
                                $req = $bdd->prepare('INSERT INTO pictures(id, fileName, productID) VALUES (?, ?, ?)');
                                $req->execute(array($_POST['idOldPictures'][$i],
                                                    $uploadFile,
                                                    $_GET['saleID']));
                            }else if($_POST['idOldPictures'][$i] == $mainPictureID){
                                $req = $bdd->prepare('UPDATE pictures SET fileName = ? WHERE id = ? AND productID = ?');
                                $req->execute(array($uploadFile,
                                                    $mainPictureID,
                                                    $_GET['saleID']));
                            }
                        }else{
                            $numberOfPictures->execute(array($_GET['saleID']));
                            if($numberOfPictures->fetch(PDO::FETCH_COLUMN) < 5){
                                $req = $bdd->prepare('INSERT INTO pictures(fileName, productID) VALUES (?, ?)');
                                $req->execute(array($uploadFile,
                                                    $_GET['saleID']));
                            }
                        }
                    }
                }

                // Mise à jour les liens des produits externes
                if(isset($_POST['idExternalProducts'])){
                    $req = $bdd->prepare('DELETE FROM externalAssociations WHERE productID = ? AND externalProductID = ?');

                    foreach ($_POST['idExternalProducts'] as $idExternalProduct)
                        $req->execute(array($_GET['saleID'],
                                            $idExternalProduct));
                }
                if(isset($_POST['url_prix'])){
                    $req = $bdd->prepare('SELECT count(id) FROM externalAssociations WHERE productID = ?');
                    $req->execute(array($_GET['saleID']));

                    $lenURLsArray = count($_POST['url_prix']);

                    if($lenURLsArray <= 5 - $req->fetch(PDO::FETCH_COLUMN)){ // On vérifie que l'utilisateur ne dépasse pas la limite de 5 produits externes
                        for($i = 0; $i < $lenURLsArray; $i++){
                            switch(extract_infos_product($_POST['url_prix'][$i], $_GET['saleID']))
                            {
                                case PLATEFORM_NOT_FOUND:
                                    echo "<p>Attention : \"" . $_POST['url_prix'][$i] . "\" Plateforme non compatible.</p>";
                                    break;
                                case BAD_MARKET:
                                    echo "<p>Attention : \"" . $_POST['url_prix'][$i] . "\" Marché non compatible.</p>";
                                    break;
                                case PRICE_404:
                                    echo "<p>Attention : \"" . $_POST['url_prix'][$i] . "\" Prix non trouvé.</p>";
                                    break;
                                case ERROR_URL:
                                    echo "<p>Attention : \"" . $_POST['url_prix'][$i] . "\" Erreur dans l'URL.</p>";
                                    break;
                                case EBAY_KEYWORD:
                                    echo "<p>Attention : \"" . $_POST['url_prix'] . "\" Erreur API, veuillez contacter l'administrateur..</p>";
                                    break;
                                case CDISCOUNT_TOKEN:
                                    if(extract_infos_product($_POST['url_prix'][$i], $_GET['saleID']) < 0)
                                    {
                                        echo "<p>Attention : \"" . $_POST['url_prix'][$i] . "\" Erreur dans l'URL.</p>";
                                        break;
                                    }
                                default:
                                    break;
                            }
                        }
                    }else
                        echo '<p>Attention : Trop d\'URLs fournies</p>';
                }

                reloadExternalPrices(array('id' => $_GET['saleID'],
                                           'marketPosition' => $_POST['marketPosition'],
                                           'saleStatus' => 0), $time);

                echo 'Mise à jour réussie.';

                $getProductInfos->execute(array($_GET['saleID'],
                                                $_SESSION['id']));

                $sale = $getProductInfos->fetch(PDO::FETCH_ASSOC);
            }
        }

        require 'php/modules/manageAnnouncement.php';
    }else
        header('location: ?');
}else if(isset($_GET['del'])){
    $req = $bdd->prepare('SELECT id FROM products WHERE id = ? AND sellerID = ?');
    $req->execute(array($_GET['del'],
                        $_SESSION['id']));

    if(!empty($req->fetch()))
    {
        $req = $bdd->prepare('DELETE FROM externalAssociations WHERE productID = ?'); // Supprimer l'association
        $req->execute(array($_GET['del']));

        $req = $bdd->prepare('DELETE FROM shoppingCart WHERE productID = ?'); // Supprimer le produit des paniers
        $req->execute(array($_GET['del']));

        $req = $bdd->prepare('DELETE FROM pictures WHERE productID = ?'); // Supprimer les images du produit
        $req->execute(array($_GET['del']));

        $req = $bdd->prepare('DELETE FROM products WHERE id = ?'); // Supprime le produit
        $req->execute(array($_GET['del']));

        echo '<p>Annonce supprimée.<br>
                    <a href="?i=sales">Retourner à vos annonce</a>
                </p>';
    }else
        header('location: ?');
}else{
    $req = $bdd->prepare('SELECT id, name, lastPrice, marketPosition, description, releaseDate, saleStatus, sellerID FROM products WHERE sellerID = ? AND saleStatus = 0');
    $req->execute(array($_SESSION['id']));

    $sales = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div>
            <h2>Vos articles mis en vente</h2>';

    if(!empty($sales)){
        require 'php/modules/blocItems.php';

        blocItems($sales);
    }

    echo '</div>';

    $req = $bdd->prepare('SELECT * FROM products WHERE sellerID = ? AND saleStatus = 1');
    $req->execute(array($_SESSION['id']));

    $sales = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div id="itemsSold">
            <h2>Vos articles vendus</h2>';

    if(!empty($sales)){
        require_once 'php/modules/blocItems.php';

        blocItems($sales);
    }

    echo '</div>
          <link rel="stylesheet" href="/css/sales.css">';
}

function mis_log($msg)
{
    echo '<p>' . $msg . '</p>';
    require 'php/modules/manageAnnouncement.php';
}
?>
