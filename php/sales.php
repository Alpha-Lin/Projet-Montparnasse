<?php
if(!isset($_SESSION['id']))
    header('location: ?i=login');

if(isset($_GET['saleID'])){
    $req = $bdd->prepare('SELECT name, marketPosition, description, conditionP, category FROM products WHERE id = ? AND sellerID = ? AND saleStatus = 0'); // Récupère et vérifie que le produit est bien mis en vente par l'utilisateur
    $req->execute(array($_GET['saleID'],
                        $_SESSION['id']));

    $sale = $req->fetch(PDO::FETCH_ASSOC);

    if(!empty($sale)){
        if(isset($sale['nom_produit'], $sale['description_produit'], $sale['etat_produit'], $sale['url_prix'], $sale['marketPosition'], $sale['categorie_produit'], $_FILES['pictures'])){
            // TODO : factoriser le code de la page upload_announcement.php
        }
        else
            require 'php/modules/manageAnnouncement.php';
    }
        
}else{
    $req = $bdd->prepare('SELECT id, name, lastPrice, marketPosition, description, releaseDate, saleStatus, sellerID FROM products WHERE sellerID = ? AND saleStatus = 0');
    $req->execute(array($_SESSION['id']));

    $sales = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div>
            <h2>Vos articles mis en vente</h2>';

    if(!empty($sales)){
        require 'php/modules/blocItems.php';

        blocItems($sales);
    }

    echo '</div>';

    $req = $bdd->prepare('SELECT * FROM products WHERE sellerID = ? AND saleStatus = 1');
    $req->execute(array($_SESSION['id']));

    $sales = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div id="itemsSold">
            <h2>Vos articles vendus</h2>';

    if(!empty($sales)){
        require_once 'php/modules/blocItems.php';

        blocItems($sales);
    }

    echo '</div>
          <link rel="stylesheet" href="/css/sales.css">';
}
?>
