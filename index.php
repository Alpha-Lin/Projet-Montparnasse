<?php     /* the front page */
require 'php/modules/init_bdd.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'php/modules/header.php';

if(isset($_GET['i']) && !empty($_GET['i']))
{
    $path_include = 'php/' . $_GET['i'] . '.php';

    if(dirname($path_include) === 'php')
        require $path_include;
}else if(isset($_GET['search']) && !empty($_GET['search']))
    require 'php/search.php';

    
    echo '<div class="scrollable">';

    echo '<div class="push"></div>';

    echo '<h1>Exemple de présentation (aligner à droite pour une potentielle sidebar)</h1>';
    echo '
    <div class="alignProduit">
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
        <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Nom</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 10 </p>
            </div>
            <div class="etatObj">
                <p>état : Bon </p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Moi </p>
            </div>
            <div class="descObj">
                <p>Description : Un très bon objet</p>
            </div>
        </div>
    </div>
    ';

    echo '<div class="push"></div>';
    echo '</div>';
require 'html/footer.html';

?>
