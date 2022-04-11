<?php
if(!isset($_SESSION['id']))
    header('location: ?i=Compte');

if(isset($_POST['nom_produit'], $_POST['description_produit'], $_POST['etat_produit'], $_POST['url_prix'], $_POST['marketPosition'], $_FILES['pictures']))
{
    if(!empty($_POST['nom_produit']) && !empty($_POST['etat_produit']) && !empty($_POST['url_prix'] && !empty($_POST['marketPosition'])))
    {
        require 'php/modules/hcaptcha.php';

        if(!hcaptcha($_POST['h-captcha-response']))
            mis_log("Captcha invalide !");
        else if(strlen($_POST['description_produit']) > 300)
            mis_log("Description trop large.");
        else if(count($_POST['url_prix']) > 5)
            mis_log("Trop d'URLs fournis !");
        else if($_POST['marketPosition'] < 1 || $_POST['marketPosition'] > 100) // Le prix ne peut être en dehors de cet intervalle
            mis_log("L'intervalle du prix est incorrect !");
        else
        {
            require 'php/modules/new_extern_product.php';

            $prix_array = array();

            $req = $bdd->prepare('INSERT INTO products(name, marketPosition, description, conditionP, sellerID) VALUES (?, ?, ?, ?, ?)');
            $req->execute(array($_POST['nom_produit'],
                                $_POST['marketPosition'],
                                $_POST['description_produit'],
                                $_POST['etat_produit'],
                                $_SESSION['id'])
            );

            $produit_id = $bdd->lastInsertId();

            mkdir("images/products/" . $produit_id);

            for($i = 0; $i < 4; $i++)
            {
                if(empty($_FILES['pictures']['name'][$i]))
                    continue;

                $uploadFile = substr($produit_id . "/" . basename($_FILES['pictures']['name'][$i]), 0, 48);

                move_uploaded_file($_FILES['pictures']['tmp_name'][$i], "images/products/" . $uploadFile);
    
                $req = $bdd->prepare('INSERT INTO pictures(fileName, productID) VALUES (?, ?)');
                $req->execute(array($uploadFile,
                                    $produit_id));
            }

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
                $req->execute(array(calculPrixMarketPosition($prix_array, $nb_prix, $_POST['marketPosition']), // Prix adapté selon la position voulue
                                    $produit_id)
                );

                echo 'Mise en ligne réussie.';
            }else{
                $req = $bdd->prepare('DELETE FROM products WHERE id = ?');
                $req->execute(array($produit_id));

                mis_log("Erreur : Aucune URL n'a fonctionné.");
            }       
        }
    }else
        mis_log("Paramètre(s) vide(s).");
}else
    require 'html/upload_announcement.html';

function mis_log($msg)
{
    echo '<p>' . $msg . '</p>';
    require 'html/upload_announcement.html';
}
?>
