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

$reqFilter = 'WHERE name LIKE :search AND saleStatus = 0 AND (releaseDate <= "2022-05-10 23:18:25" OR idGroupe = :idGroupe)';

if(isset($_GET['category']))
    $reqFilter .= ' AND category = :category ';

if(isset($_GET['filtres'])){
    switch($_GET['filtres']){
        case 'seller':
            $reqFilter = $reqFilter . ' AND reputation > 2.5';
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

$req = $bdd->prepare('SELECT P.id, name, lastPrice, marketPosition, P.description, releaseDate, conditionP, saleStatus, sellerID FROM products AS P JOIN users ON P.sellerID = users.id ' . $reqFilter . ' ORDER BY P.premium DESC' . $reqOrder . ' LIMIT :limitRes');
$req->bindValue(':search', '%' . $_GET['search'] . '%');
$req->bindValue(':idGroupe', $_SESSION['stonks-me-id']);
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

if(isset($_GET['category']) && empty($_GET['search']))
    echo '<h2 class="recherche">Résultat pour la catégorie : "' . htmlspecialchars($_GET['category']) . '"</h2>';
else
    echo '<h2 class="recherche">Résultat de recherche pour : "' . htmlspecialchars($_GET['search']) . '"</h2>';

if(!empty($produits_research))
{
    require 'php/modules/blocItems.php';

    blocItems($produits_research);
}else
    echo '<p>Aucun résultat trouvé.</p>';
?>
