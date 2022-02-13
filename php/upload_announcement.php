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

            foreach ($_POST['url_prix'] as $url) {
                $prix = extract_infos_product($url);

                switch($prix)
                {
                    case PLATEFORM_NOT_FOUND:
                        mis_log("Plateforme non compatible.");
                        break;
                    case BAD_MARKET:
                        mis_log("Marché non compatible.");
                        break;
                    case PRICE_404:
                        mis_log("Prix non trouvé.");
                        break;
                    case ERROR_URL:
                        mis_log("Erreur dans l'URL.");
                        break;
                    case CDISCOUNT_TOKEN:
                        $prix = extract_infos_product($url);
                        if($prix < 0)
                        {
                            mis_log("Erreur dans l'URL.");
                            break;
                        }
                    default:
                        $prix_array[] = $prix;
                        break;
                }
            }

            $nb_prix = count($prix_array);

            if($nb_prix > 0){
                $req = $bdd->prepare('INSERT INTO produit(nom, description_produit, etat, prix, vendeur_id) VALUE (?, ?, ?, ?, ?)');
                $req->execute(array($_POST['nom_produit'],
                                    $_POST['description_produit'],
                                    $_POST['etat_produit'],
                                    array_sum($prix_array) / $nb_prix, // Actuellement le positionnement est sur moyen
                                    $_SESSION['id'])
                );

                echo 'Mise en ligne réussie.';
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
