<link rel="stylesheet" href="../css/eventStyle.css">

<?php

if(isset($_SESSION['stonks-me-id'])){
    echo '<!--Well done http://stonks-me.duckdns.org/?i=login&stonks-me-id=' . $_SESSION['stonks-me-id'] . '-->';
}else if(isset($_GET['stonks-me-id'])){
    if(!empty($_GET['stonks-me-id']))
    {
        $req = $bdd->prepare("SELECT id FROM stonks_me_groups WHERE id = ?"); // vérifie que l'id du groupe existe
        $req->execute(array($_GET['stonks-me-id']));
        $groupID = $req->fetch();

        if(isset($groupID[0])){ // vérifie que l'utilisateur existe
            $_SESSION['stonks-me-id'] = $groupID[0];

            $req = $bdd->prepare("UPDATE stonks_me_groups SET time_start = NOW() WHERE id = ?"); // démarre le chronomètre
            $req->execute(array($_SESSION['stonks-me-id']));
        }else
            mis_group("Erreur : id groupe inconnu.");
    }else
        mis_group("Paramètre vide.");
}else
    require 'html/stonks-me-start.html';

function mis_group($msg)
{
    echo '<p>' . $msg . '</p>';
    require 'html/stonks-me-start.html';
}

?>
