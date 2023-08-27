<?php
    if (isset($_POST['flag']) && !empty($_POST['flag'])) {
        if ($_POST['flag'] === "essapedtom") {
            $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 8 WHERE id = ?");
            $req->execute(array($_SESSION['stonks-me-id']));

            require 'html/fin.html';
        }
        else {
            require 'html/flag.html';
        }
    }
    else {
        require 'html/flag.html';
    }
    
?>