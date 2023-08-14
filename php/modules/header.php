<?php
require 'html/header.html';

if(isset($_SESSION['id'])){
    $req = $bdd->prepare('SELECT COUNT(id) FROM products WHERE sellerID = ?');
    $req->execute(array($_SESSION['id']));

    $isVendor = $req->fetch(PDO::FETCH_COLUMN) > 0;

    echo '<a href="?i=Compte" class="compte" id="utilisateur"><i class="fa fa-user" id="iconeU" aria-hidden="true"></i></a>
          <div id="compteDropDown">
            <a href="?i=Compte">Mon profil</a>' .
            ($isVendor ? '<a href="?i=sales">Mes ventes</a>' : '') .
            '<a href="logout.php">Déconnexion</a>
          </div>
          <a href="?i=panier" id="panier" style="float: right"><i class="fa fa-shopping-basket" id="iconeB" aria-hidden="true"></i></a>
          <a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a>';
}
?>

</nav> 

<?php
    if(isset($_GET['search']) && !empty($_GET['search']))
        require 'html/slide_down_menu.html';
?>

<main>
