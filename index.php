<?php
require 'php/modules/init_bdd.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'php/modules/header.php';

if (isset($_SESSION['stonks-me-id'])) {
    if($stepLocked > 1)
        echo '<!--Well done https://stonks-me.duckdns.org/?i=login&stonks-me-id=' . $_SESSION['stonks-me-id'] . '-->';

    if($_SESSION['stonks-me-step'] != 8) // Vérifie que le groupe n'a pas terminé
    {
        if($stepLocked == $_SESSION['stonks-me-step'])
            echo '<p>La prochaine étape n\'est pas disponible.</p>';
        else{
            $enonces = [
                "Ah, j'aimerais bien accéder au reste du site, mais je suis bloqué. Où est le lien ?",
                "Hmm, mon user-agent est incorrect. Heureusement j'ai un outil pour m'aider ",
                "Voici ma première victime, voyons si je peux facilement me connecter",
                "Un code de confirmation ? Je ne crois pas voir de lien pour le trouver...",
                "Super, je suis connecté ! Je pense que je vais essayer de voir si je ne peux pas faire quelque chose en mettant une annonce en ligne...",
                "Le piège est en place, attendons que l'administrateur morde à l'hameçon.",
                "",
                "Me voilà sur la session de l'administrateur, je crois que je peux utiliser un terminal quelque part"
            ];

            echo '<p class="enonce">' . (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] && $_SESSION['stonks-me-step'] == 6 ? $enonces[7] : $enonces[$_SESSION['stonks-me-step'] - 1]) . '</p>';

            $req = $bdd->prepare("SELECT indice FROM stonks_me_groups WHERE id = ?"); // Vérifie si l'indice est activé
            $req->execute(array($_SESSION['stonks-me-id']));

            $indice = $req->fetch()[0];

            if($indice == $_SESSION['stonks-me-step']){
                $indices = [
                    "À la source de nos problèmes",
                    "Voyons voir ma boite à outils",
                    "Do Not Repeat Yourself",
                    "Comme un explorateur de fichier",
                    "JS et le champ des possibles",
                    "J'ai faim, je me prendrais bien un petit cookie",
                    "",
                    "Prenez garde au point virgule"
                ];

                echo '<p class="indice">' . (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] && $_SESSION['stonks-me-step'] == 6 ? $indices[7] : $indices[$indice - 1]) . '</p>';
            }
        }
    }

    if(isset($_GET['i']) && !empty($_GET['i']))
    {
        $path_include = 'php/' . $_GET['i'] . '.php';

        if(dirname($path_include) === 'php' && file_exists($path_include))
            require $path_include;
        else
            require 'php/carousel.php';
    }else if(isset($_GET['search']))
        require 'php/search.php';
    else
        require 'php/carousel.php';
}
else 
    require 'php/modules/stonks-me-start.php';

require 'html/footer.html';
?>
