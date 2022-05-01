<link rel="stylesheet" href="/css/search.css">

<?php
$reqOrder = '';

if(isset($_GET['tri'])){
    switch($_GET['tri']){
        case 'priceASC':
            $reqOrder = ', lastPrice ASC';
            break;
        case 'priceDESC':
            $reqOrder = ', lastPrice DESC';
            break;
        case 'bestToWorth':
            $reqOrder = ', conditionP ASC';
            break;
        case 'worthToBest':
            $reqOrder = ', conditionP DESC';
            break;
        default:
            break;
    }
}

$reqFilter = 'WHERE name LIKE :search AND saleStatus = 0';

if(isset($_GET['category']))
    $reqFilter .= ' AND category = :category ';

if(isset($_GET['filtres'])){
    switch($_GET['filtres']){
        case 'seller':
            $reqFilter = 'JOIN users ON sellerID = users.id ' . $reqFilter . ' AND reputation > 2.5';
            break;
        case 'marketPrice':
            $reqFilter .= ' AND lastPrice <= (SELECT AVG(lastPrice) FROM products WHERE name LIKE :search)';
            break;
        case 'delivering':
            $reqFilter .= ' AND 1=1'; // TODO later
            break;
        case 'lessThanFive':
            $reqFilter .= ' AND lastPrice <= 5';
            break;
        default:
            break;
    }
}

$req = $bdd->prepare('SELECT products.id, name, products.description, releaseDate, sellerID, conditionP, marketPosition FROM products ' . $reqFilter . ' ORDER BY products.premium DESC' . $reqOrder . ' LIMIT :limitRes');
$req->bindValue(':search', '%' . $_GET['search'] . '%');
if(isset($_GET['category']))
    $req->bindValue(':category', $_GET['category']);

if(isset($_GET['nbResult'])){
    switch($_GET['nbResult']){
        case '12':
            $req->bindValue(':limitRes', 12, PDO::PARAM_INT);
            break;
        case '32':
            $req->bindValue(':limitRes', 32, PDO::PARAM_INT);
            break;
        case '64':
            $req->bindValue(':limitRes', 64, PDO::PARAM_INT);
            break;
        case '128':
            $req->bindValue(':limitRes', 128, PDO::PARAM_INT);
            break;
        default:
            $req->bindValue(':limitRes', 9, PDO::PARAM_INT);
            break;
    }
}else
    $req->bindValue(':limitRes', 9, PDO::PARAM_INT);

$req->execute();

$produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

echo '<h2 class="recherche">Résultat de recherche pour : "' . htmlspecialchars($_GET['search']) . '"</h2>';

if(!empty($produits_research))
{
    require 'php/modules/price.php';

    $req_pseudo_vendeur = $bdd->prepare('SELECT pseudo FROM users WHERE id = ?');
    $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

    echo '<div class="grilleProduits">';

    $temps = time();

    foreach ($produits_research as $produit) { // Chaque produit
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
                            <p>De <a href="#" class="vendeur">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</a><br>le ' . $produit['releaseDate'] . '</p>
                        </div>

                        <p class="descProduit">' . htmlspecialchars($produit['description']) . '</p>

                        <p class="prixProduit">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                    </div>
                </div>

                <p class="ajoutPanier"><a href="?i=panier&add=' . $produit['id'] . '">Ajouter au panier</a></p>

                
            </div>'; // TODO : envoyer le dernier prix pour montrer l'évolution
    }

    echo '</div>';
}else
    echo '<p>Aucun résultat trouvé.</p>';
?>
