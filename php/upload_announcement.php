<?php
if(!isset($_SESSION['id']))
    header('location: index.php?i=Compte');
else if(isset($_POST['nom_produit'], $_POST['description_produit'], $_POST['etat_produit'], $_POST['url_prix']))
{
    if(!empty($_POST['nom_produit']) && !empty($_POST['etat_produit']) && !empty($_POST['url_prix']))
    {
        require 'php/modules/hcaptcha.php';

        if(!hcaptcha($_POST['h-captcha-response']))
            mis_log("Captcha invalide !");
        else if(strlen($_POST['description_produit']) > 300)
            mis_log("Description trop large.");
        else if(count($_POST['url_prix']) > 5)
            mis_log("Trop d'URLs fournis !");
        else
        {
            require 'php/modules/new_extern_product.php';

            $prix_array = array();

            $req = $bdd->prepare('INSERT INTO produit(nom, description_produit, etat, prix, vendeur_id) VALUE (?, ?, ?, 0, ?)');
            $req->execute(array($_POST['nom_produit'],
                                $_POST['description_produit'],
                                $_POST['etat_produit'],
                                $_SESSION['id'])
            );

            $produit_id = $bdd->lastInsertId();

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
                $req = $bdd->prepare('UPDATE produit SET prix = ? WHERE id = ?');
                $req->execute(array(array_sum($prix_array) / $nb_prix, // Actuellement le positionnement est sur moyen
                                    $produit_id)
                );

                echo 'Mise en ligne réussie.';
            }else{
                $req = $bdd->prepare('DELETE FROM produit WHERE id = ?');
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
