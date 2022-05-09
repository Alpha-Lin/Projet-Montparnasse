<?php
if(!isset($_SESSION['id']))
    header('location: ?i=Compte');

if(isset($_POST['nom_produit'], $_POST['description_produit'], $_POST['etat_produit'], $_POST['url_prix'], $_POST['marketPosition'], $_POST['categorie_produit'], $_FILES['pictures']))
{
    if(!empty($_POST['nom_produit']) && !empty($_POST['etat_produit']) && !empty($_POST['url_prix'] && !empty($_POST['marketPosition'])))
    {
        require 'php/modules/hcaptcha.php';

        if(!hcaptcha($_POST['h-captcha-response']))
            mis_log("Captcha invalide !");
        else if(strlen(utf8_decode($_POST['description_produit'])) > 300)
            mis_log("Description trop large.");
        else if(count($_POST['url_prix']) > 5)
            mis_log("Trop d'URLs fournis !");
        else if($_POST['marketPosition'] < 1 || $_POST['marketPosition'] > 100) // Le prix ne peut être en dehors de cet intervalle
            mis_log("L'intervalle du prix est incorrect !");
        else
        {
            require 'php/modules/new_extern_product.php';

            $prix_array = array();

            $req = $bdd->prepare('INSERT INTO products(name, marketPosition, description, conditionP, category, sellerID) VALUES (?, ?, ?, ?, ?, ?)');
            $success = $req->execute(array($_POST['nom_produit'],
                                $_POST['marketPosition'],
                                $_POST['description_produit'],
                                $_POST['etat_produit'],
                                $_POST['categorie_produit'],
                                $_SESSION['id'])
            );

            if(!$success)
                mis_log("Erreur lors de l'insertion des données !");
            else{
                $produit_id = $bdd->lastInsertId();

                mkdir("images/products/" . $produit_id);

                $time = time();

                $success = false;

                for($i = 0; $i < 4; $i++)
                {
                    if(empty($_FILES['pictures']['name'][$i]))
                        continue;
                    else if(!getimagesize($_FILES['pictures']['tmp_name'][$i]))
                        echo '<p>Attention : Image ' . ($i + 1) .', le fichier envoyé n\'est pas une image !</p>';
                    else if($_FILES['pictures']['size'][$i] > 16777216) // Inférieur à 16 Mo
                        echo '<p>Attention : Image ' . ($i + 1) .', fichier trop lourd, taille maximum 16 Mo.</p>';
                    else{
                        $uploadFile = substr($produit_id . "/" . $time . basename($_FILES['pictures']['name'][$i]), 0, 48);

                        move_uploaded_file($_FILES['pictures']['tmp_name'][$i], "images/products/" . $uploadFile);
            
                        $req = $bdd->prepare('INSERT INTO pictures(fileName, productID) VALUES (?, ?)');
                        if($req->execute(array($uploadFile, $produit_id)))
                            $success = true;
                    }
                }

                if(!$success)
                    mis_log("Erreur : aucune image n'a pu être insérée !", $product_id);
                else{
                    foreach ($_POST['url_prix'] as $url) {
                        $prix = extract_infos_product($url, $produit_id);

                        switch($prix)
                        {
                            case PLATEFORM_NOT_FOUND:
                                echo "<p>Attention : \"" . $url . "\" Plateforme non compatible.</p>";
                                break;
                            case BAD_MARKET:
                                echo "<p>Attention : \"" . $url . "\" Marché non compatible.</p>";
                                break;
                            case PRICE_404:
                                echo "<p>Attention : \"" . $url . "\" Prix non trouvé.</p>";
                                break;
                            case ERROR_URL:
                                echo "<p>Attention : \"" . $url . "\" Erreur dans l'URL.</p>";
                                break;
                            case EBAY_KEYWORD:
                                echo "<p>Attention : \"" . $url . "\" Erreur API, veuillez contacter l'administrateur..</p>";
                                break;
                            case CDISCOUNT_TOKEN:
                                $prix = extract_infos_product($url, $produit_id);
                                if($prix < 0)
                                {
                                    echo "<p>Attention : \"" . $url . "\" Erreur dans l'URL.</p>";
                                    break;
                                }
                            default:
                                $prix_array[] = $prix;
                                break;
                        }
                    }

                    $nb_prix = count($prix_array);

                    if($nb_prix > 0){
                        sort($prix_array);

                        $req = $bdd->prepare('UPDATE products SET lastPrice = ? WHERE id = ?');
                        $success = $req->execute(array(calculPrixMarketPosition($prix_array, $nb_prix, $_POST['marketPosition']), // Prix adapté selon la position voulue
                                                       $produit_id)
                        );

                        if($success)
                            echo '<p>Mise en ligne réussie.</p>';
                        else
                            mis_log("<p>Erreur pendant l'inscription du prix !</p>", $product_id);
                    }else
                        mis_log("Erreur : Aucune URL n'a fonctionné.", $product_id); 
                }
            }  
        }
    }else
        mis_log("Paramètre(s) vide(s).");
}else
    require 'html/upload_announcement.html';

function mis_log($msg, $product_id = false)
{
    if($product_id !== false){
        global $bdd;

        $req = $bdd->prepare('DELETE FROM products WHERE id = ?');
        $req->execute(array($produit_id));
    }

    echo '<p>' . $msg . '</p>';
    require 'html/upload_announcement.html';
}
?>
