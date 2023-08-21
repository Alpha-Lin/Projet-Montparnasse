<?php
    $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 6 WHERE id = ?");
    $req->execute(array($_SESSION['stonks-me-id']));

    require 'html/flag.html';
?>