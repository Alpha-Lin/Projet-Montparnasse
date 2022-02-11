<?php
if(!isset($_SESSION['id']))
    header('location: index.php?i=Compte');
else if(isset($_POST['nom_produit'], $_POST['description_produit'], $_POST['etat_produit'], $_POST['url_prix']))
{
    if(!empty($_POST['nom_produit']) && !empty($_POST['etat_produit']) && !empty($_POST['url_prix']))
    {
        if(strlen($_POST['description_produit']) > 300)
            mis_log("Description trop large.");
        else
        {
            require 'php/modules/new_extern_product.php';

            $prix = extract_infos_product($_POST['url_prix']);

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
                    mis_log("token cdiscount mort, TODO !");
                    break;
                default:
                    $req = $bdd->prepare('INSERT INTO produit(nom, description_produit, etat, prix, vendeur_id) VALUE (?, ?, ?, ?, ?)');
                    $req->execute(array($_POST['nom_produit'],
                                        $_POST['description_produit'],
                                        $_POST['etat_produit'],
                                        $prix,
                                        $_SESSION['id'])
                    );
                    echo 'Mise en ligne réussie.';
                    break;
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