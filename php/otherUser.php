<?php
if(!isset($_GET['id']))
    header('location: ?');

$req = $bdd->prepare("SELECT pseudo, country, registerDate, reputation, sales, purchases, picture, description, `rank`, score FROM users WHERE id = ?");
$req->execute(array($_GET['id']));
$userInfos = $req->fetch(PDO::FETCH_ASSOC);

if(empty($userInfos))
    header('location: ?');
?>

<link rel="stylesheet" href="/css/addresses_and_bankCards.css">
<link rel="stylesheet" href="/css/otherUser.css">

<div class="headAccount">
    <h2 class="profilText">Utilisateur: <?=$userInfos['pseudo']?></h2>

    <div class="subHeadAccont_1">
        <div class="iconAccount noCursor">
            <img src="<?=$userInfos['picture'] === NULL ? "svg/avatar.svg" : "data:image;base64," . base64_encode($userInfos['picture'])?>" width="128" height="128">
        </div>

        <?php
            require 'php/modules/etoile.php';
            echo reputationStars($userInfos['reputation']);
        ?>
    </div>

    <div>
        <img src="svg/medals/<?=strtolower($userInfos['rank'])?>-medal.svg" class="iconRank" width="128" height="128">
        <p><span class="boldInfo">Score :</span> <?=$userInfos['score']?> pts<br/>
        <span class="boldInfo">Nombre de ventes :</span> <?=$userInfos['sales']?><br/>
        <span class="boldInfo">Nombre d'achats :</span> <?=$userInfos['purchases']?></p>
    </div>
</div>


<?php

$req = $bdd->prepare('SELECT products.id, name, lastPrice, marketPosition, products.description, releaseDate, conditionP, saleStatus, sellerID FROM products WHERE sellerID = ? AND saleStatus = 0');
$req->execute(array($_GET['id']));

$produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

if(!empty($produits_research))
{
    require 'php/modules/price.php';

    echo '<div>
            <h3>Les articles en vente par '.$userInfos['pseudo'].'</h3>';
    
    require 'php/modules/blocItems.php';

    blocItems($produits_research);

    echo '</div>';
}
else
    echo '<p>Aucun produit en vente.</p>';

?>

<div>
    <h3>Autres informations</h3>

    <div id="formInfosAccount">
            <label>
                <p class="titleInfo">Pays :</p>
                <span class="edit_input"><?=$userInfos['country']?></span> <!-- Entrée à contrôler -->
                <select class="edit_input" name="pays" selected="<?=$userInfos['country']?>" required hidden>
                    <option value="<?=$userInfos['country']?>"><?=$userInfos['country']?></option>
                    <?php require 'html/liste_pays.html';?>
                </select>
            </label>
            <label><p>Date d'inscription : </p><?=$userInfos['registerDate']?></label>
            <label>
                <p class="titleInfo">Description :</p> <span class="edit_input"><?=htmlspecialchars($userInfos['description'])?></span>
                <textarea class="edit_input" name="description_perso" maxlength="300" hidden><?=$userInfos['description']?></textarea>
            </label>
    </div>
</div>

<?php

$req = $bdd->prepare('SELECT rating, comment, notes.releaseDate, users.id, pseudo, picture, `rank` FROM notes JOIN purchases ON purchaseID = purchases.id JOIN products ON productID = products.id JOIN users ON buyerID = users.id WHERE sellerID = ?');
$req->execute(array($_GET['id']));

$ratesInfos = $req->fetchAll(PDO::FETCH_ASSOC);

if(!empty($ratesInfos)){
    echo '<link rel="stylesheet" href="/css/rates.css">
    
          <div>
            <h2>Les avis sur ' . $userInfos['pseudo'] . '</h2>';

    foreach ($ratesInfos as $rate) {
        echo '<div class="rates">

                <div>
                    <p class="rater">
                        <a href="?i=otherUser&id=' . $rate['id'] . '" class="raterHead">
                            <img class="littlePDP" src="' . ($rate['picture'] === NULL ? "svg/avatar.svg" : "data:image;base64," . base64_encode($rate['picture'])) . '" width="64" height="64">' . 
                            $rate['pseudo'] .
                       '</a>
                        <img src="svg/medals/' . strtolower($userInfos['rank']) . '-medal.svg" class="iconRank" width="64" height="64">
                    </p>
                </div>
                
                <div class="commentAndStars">
                    <p>' . $rate['comment'] . '</p>';

        echo        reputationStars($rate['rating']) .
               '</div>
                
              </div>';
    }

    echo '</div>';
}
?>

<link rel="stylesheet" href="/css/compte.css">

