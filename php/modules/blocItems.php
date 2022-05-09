<link rel="stylesheet" href="/css/search.css">

<?php

function blocItems($produits){
    require_once 'php/modules/price.php';

    global $bdd;

    $req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
    $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

    echo '<div class="grilleProduits">';

    $temps = time();

    foreach ($produits as $produit) { // Chaque produit
        $req_pseudo_vendeur->execute(array($produit['sellerID']));
        $req_main_picture->execute(array($produit['id']));

        echo '
            <div class="produit">
                
                <h3 class="titreProduit"><a href="?i=product&id=' . $produit['id'] . '">' . htmlspecialchars($produit['name']) . '</a></h3>
                <hr>
                <div class="detailsProduit">
                    <a href="?i=product&id=' . $produit['id'] . '">
                        <img class="imageProduit" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '" alt="Image de ' . htmlspecialchars($produit['name']) . '">
                    </a>
                    <div class="infosProduit">
                        <div class="vendeurProduit">
                            <p>De <a href="?i=otherUser&id=' . $produit['sellerID'] . '" class="vendeur">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</a><br>le ' . $produit['releaseDate'] . '</p>
                        </div>
                        <p class="descProduit">' . htmlspecialchars($produit['description']) . '</p>
                        <p class="prixProduit">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                    </div>
                </div>';

        if($produit['saleStatus'] == 0){
            if(!isset($_SESSION['id']) || $_SESSION['id'] != $produit['sellerID'])
                echo '<p class="ajoutPanier"><a href="?i=panier&add=' . $produit['id'] . '">Ajouter au panier</a></p>';
            else
                echo '<p class="ajoutPanier">
                        <a href="?i=sales&saleID=' . $produit['id'] . '"><img src="svg/edit.svg" alt="éditer une annonce" width="30"></a>
                        <a href="?i=sales&del=' . $produit['id'] . '"><img src="svg/remove-button.svg" alt="Supprimer une annnonce" width="30"></a>
                      </p>';
        }
                
        echo '</div>'; // TODO : envoyer le dernier prix pour montrer l'évolution
    }

    echo '</div>';
}
?>
