<?php
    if (isset($_POST['flag']) && !empty($_POST['flag'])) {
        $flag_path = fopen('flags/flag_' . $_SESSION['stonks-me-id'] . '.txt', 'r');
        $flag = fgets($flag_path);

        if ($_POST['flag'] === $flag) {
            if($_SESSION['stonks-me-step'] == 6){
                $req = $bdd->prepare("UPDATE stonks_me_groups SET time_end = NOW(), step = 7 WHERE id = ?"); // Arrête le chronomètre
                $req->execute(array($_SESSION['stonks-me-id']));

                $_SESSION['stonks-me-step'] = 7;
            }

            require 'html/fin.html';
        }
        else 
            require 'html/flag.html';

        fclose($flag_path);
    }
    else
        require 'html/flag.html';
?>
