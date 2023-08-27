<?php

if(isset($_GET['stonks-me-id'])){
    if(!empty($_GET['stonks-me-id']))
    {
        $req = $bdd->prepare("SELECT id, step, session FROM stonks_me_groups WHERE id = ?"); // vérifie que l'id du groupe existe
        $req->execute(array($_GET['stonks-me-id']));
        $groupInfos = $req->fetch();

        if(isset($groupInfos[0])){ // vérifie que l'utilisateur existe
            $req = $bdd->prepare("SELECT stepLock FROM stonks_me_sessions WHERE id = ?"); // Récupère l'étape bloquée
            $req->execute(array($groupInfos[2]));

            if($req->fetch()[0] > 0){ // Si la session a démarré
                $_SESSION['stonks-me-id'] = $groupInfos[0];
                $_SESSION['stonks-me-step'] = $groupInfos[1];
                $_SESSION['stonks-me-session'] = $groupInfos[2];

                if($_SESSION['stonks-me-step'] == 0){
                    $req = $bdd->prepare("UPDATE stonks_me_groups SET time_start = NOW(), step = 1 WHERE id = ?"); // démarre le chronomètre
                    $req->execute(array($_SESSION['stonks-me-id']));

                    $_SESSION['stonks-me-step'] = 1;
                }

                header('location: ?');
            }
            else
                mis_group('La session n\'a pas encore commencé.');
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
