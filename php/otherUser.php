<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/compte.css">
<link rel="stylesheet" href="/css/addresses_and_bankCards.css">
<?php

if(!isset($_GET['id']))
    header('location: ?');

$req = $bdd->prepare("SELECT pseudo, lastName, firstName, country, registerDate, reputation, sales, purchases, picture, description, `rank`, score FROM users WHERE id = ?");
$req->execute(array($_GET['id']));
$userInfos = $req->fetch(PDO::FETCH_ASSOC);

?>

<div class="headAccount">
    <h3 class="profilText">Utilisateur: <?=$userInfos['pseudo']?></h3>

    <div class="subHeadAccont_1">
        <div class="iconAccount noCursor">
            <img src="<?=$userInfos['picture'] === NULL ? "svg/avatar.svg" : "data:image;base64," . base64_encode($userInfos['picture'])?>" width="128" height="128">
        
        </div>

        <p><?=$userInfos['firstName'] . ' ' . $userInfos['lastName']?></p>
        <p>Rang : <?=$userInfos['rank']?></p>
        <?php
            require 'php/modules/etoile.php';
            reputationStars($userInfos['reputation']);
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


?>