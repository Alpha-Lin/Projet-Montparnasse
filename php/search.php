<link rel="stylesheet" href="/css/search.css">

<?php
// TODO : corriger la recherche
$req = $bdd->prepare('SELECT id, name, description, releaseDate, sellerID, conditionP, marketPosition FROM products WHERE name LIKE ? ORDER BY premium LIMIT 9');
$req->execute(array('%' . $_GET['search'] . '%'));

$produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

if(!empty($produits_research))
{
    require 'php/modules/price.php';

    $req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
    $req_produits_externes = $bdd->prepare('SELECT externalProductID FROM externalAssociations WHERE productID = ?');
    $req_last_refresh_produit_externe = $bdd->prepare('SELECT lastRefresh FROM externalProducts WHERE id = ?');
    $req_prix_externe = $bdd->prepare('SELECT price FROM externalProducts WHERE id = ?');
    $req_update_final_price = $bdd->prepare('UPDATE products SET lastPrice = ? WHERE id = ?');
    $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');
    
    echo '<h2 class="recherche">Résultat de recherche pour : "' . htmlspecialchars($_GET['search']) . '"</h2>
            <div class="grilleProduits">';

    $temps = time();

    foreach ($produits_research as $produit) { // Chaque produit
        $prix = 0;

        $req_produits_externes->execute(array($produit['id']));

        $prix_array = array();

        foreach ($req_produits_externes->fetchAll(PDO::FETCH_ASSOC) as $produit_externe) { // Chaque produit externe du produit
            $req_last_refresh_produit_externe->execute(array($produit_externe['externalProductID']));

            if($temps - strtotime($req_last_refresh_produit_externe->fetch(PDO::FETCH_COLUMN)) > 604800) // Si ça fait plus de 1 semaine
                update_price($produit_externe['externalProductID']); // Mise à jour du prix du produit externe

            $req_prix_externe->execute(array($produit_externe['externalProductID']));

            $prix_array[] = $req_prix_externe->fetch(PDO::FETCH_COLUMN);
        }

        $prix = calculPrixMarketPosition($prix_array, count($prix_array), $produit['marketPosition']);

        $req_update_final_price->execute(array($prix,
                                               $produit['id'])); // Mise à jour du dernier prix

        $req_pseudo_vendeur->execute(array($produit['sellerID']));
        $req_main_picture->execute(array($produit['id']));

        echo '
            <div class="produit">
                
                <h3 class="titreProduit">' . htmlspecialchars($produit['name']) . '</h3>

                <hr>

                <div class="detailsProduit">
                    <img class="imageProduit" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '">

                    <div class="infosProduit">
                        <div class="vendeurProduit">
                            <p>De <a href="#" class="vendeur">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</a><br>le ' . $produit['releaseDate'] . '</p>
                        </div>

                        <p class="descProduit">' . htmlspecialchars($produit['description']) . '</p>

                        <p class="prixProduit">' . $prix . '€</p>
                    </div>
                </div>

                <p class="ajoutPanier">Ajouter au panier</p>

                
            </div>'; // TODO : envoyer le dernier prix pour montrer l'évolution
    }

    echo '</div>';
}else
    echo '<p>Aucun résultat trouvé.</p>';

?>
