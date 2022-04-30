<link rel="stylesheet" href="css/search.css">

<h2>Derniers produits mis en vente :</h2>

<div class="carousel">
    <div class="center" id="center">
        <div class="cadre" id="cadre">
<?php

$req = $bdd->query('SELECT id, name, description, releaseDate, sellerID, conditionP, marketPosition FROM products WHERE saleStatus = 0 ORDER BY releaseDate DESC, premium DESC LIMIT 6');

require 'php/modules/price.php';

$req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
$req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

$temps = time();

foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $produit) {
    $req_pseudo_vendeur->execute(array($produit['sellerID']));
    $req_main_picture->execute(array($produit['id']));

    echo    '<div class="produitCarousel">

            <h3 class="titreProduitCarousel"><a href="?i=product&id=' . $produit['id'] . '">' . htmlspecialchars($produit['name']) . '</a></h3>

            <div class="detailsProduitCarousel">
                <a href="?i=product&id=' . $produit['id'] . '">
                    <img class="imageProduitCarousel" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '">
                </a>

                <div class="infosProduitCrousel">
                    <div class="vendeurProduitCarousel">
                        <p>De <a href="#" class="vendeurCarousel">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</a><br>le ' . $produit['releaseDate'] . '</a></p>
                    </div>

                    <p class="descProduitCarousel">' . htmlspecialchars($produit['description']) . '</p>

                    <p class="prixProduitCarousel">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                </div>
            </div>

            <p class="ajoutPanierCarousel"><a href="?i=panier&add=' . $produit['id'] . '">Ajouter au panier</a></p>


        </div>';
}
?>
        </div> 
        <div class="btnMap">
            <button class="first"></button>
            <button class="second"></button>
            <button class="third"></button>
        </div>
    </div>
</div>

<script src="js/carousel.js"></script>
