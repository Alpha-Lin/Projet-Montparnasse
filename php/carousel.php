<link rel="stylesheet" href="css/search.css">
<link rel="stylesheet" href="css/style.css">

<h2>Derniers produits mis en vente :</h2>

<div class="carousel">
    <div class="center" id="center">
        <div class="cadre" id="cadre">
<?php

$req = $bdd->prepare('SELECT P.id, name, lastPrice, marketPosition, P.description, releaseDate, sellerID, conditionP, saleStatus FROM products AS P JOIN users ON P.sellerID = users.id WHERE saleStatus = 0 AND (releaseDate <= "2022-05-10 23:18:25" OR idGroupe = ?) ORDER BY releaseDate DESC, P.premium DESC LIMIT 6');
$req->execute(array($_SESSION['stonks-me-id']));

require 'php/modules/price.php';

$req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
$req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

$temps = time();

foreach (array_slice($req->fetchAll(PDO::FETCH_ASSOC), 0, 3) as $produit) {
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
                        <p>De <a href="?i=otherUser&id=' . $produit['sellerID'] . '" class="vendeurCarousel">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</a><br>le ' . $produit['releaseDate'] . '</a></p>
                    </div>

                    <p class="descProduitCarousel">' . htmlspecialchars($produit['description']) . '</p>

                    <p class="prixProduitCarousel">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                </div>
            </div>';

    if(!isset($_SESSION['id']) || $_SESSION['id'] != $produit['sellerID'])
        echo '<p class="ajoutPanierCarousel"><a href="?i=panier&add=' . $produit['id'] . '">Ajouter au panier</a></p>';

    echo '</div>';
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

<?php
/*
Récupère les produits non vendus qui :
    - sont à moins de 300€
    - ont une description supérieure à 50 caractères
    - ont été mis en ligne il y a moins d'un mois
    - sont minimum en "Bon état"
    - ont une catégorie précise
*/
$req = $bdd->prepare('SELECT P.id, name, lastPrice, marketPosition, P.description, releaseDate, conditionP, saleStatus, sellerID FROM products AS P JOIN users ON P.sellerID = users.id WHERE lastPrice < 300 AND LENGTH(P.description) > 50 AND (releaseDate > DATE_SUB("2022-05-10 23:18:25", INTERVAL 1 MONTH) OR releaseDate > DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND conditionP IN ("Neuf", "Comme neuf", "Très bon état", "Bon état") AND category <> "Autre" AND saleStatus = 0 AND (releaseDate <= "2022-05-10 23:18:25" OR idGroupe = ?) LIMIT 9');
$req->execute(array($_SESSION['stonks-me-id']));

$produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

if(!empty($produits_research))
{
    echo '<h3>Articles recommandés :</h3>';
    require 'php/modules/blocItems.php';

    blocItems($produits_research);
}
?>
