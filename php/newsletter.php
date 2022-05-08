<?php

if(isset($_POST['add'])){
    $req = $bdd->prepare('INSERT INTO newsletter(mailSub) VALUE(?)');
    $req->execute(array($_POST['add']));
    echo '<p>Email enregistré à la newsletter</p>';
}else if(isset($_POST['remove'])){
    $req = $bdd->prepare('DELETE FROM newsletter WHERE mailSub = ?');
    $req->execute(array($_POST['remove']));
    echo '<p>Email desinscrit à la newsletter</p>';
}

?>