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

    echo '
    <div class="scrollable">
        <div class="alignProduit">
            <div class="produit">
                <div class="imgObj">
                    <p>IMAGE</p>
                </div>
                <div class="titreObj">
                    <a href="#"><h2>Canapé-lit</h2></a>
                </div>
                <div class="prixObj">
                    <p>Prix : 472.67</p>
                </div>
                <div class="etatObj">
                    <p>état : Bon état</p>
                </div>
                <div class="vendeurObj">
                    <p>Vendeur : Alpha-Lin</p>
                </div>
                <div class="descObj">
                    <p>Description : Canapé-lit pratique pour les fêtes.</p>
                </div>
            </div>
            <div class="produit">
            <div class="imgObj">
                <p>IMAGE</p>
            </div>
            <div class="titreObj">
                <a href="#"><h2>Canapé-lit</h2></a>
            </div>
            <div class="prixObj">
                <p>Prix : 472.67</p>
            </div>
            <div class="etatObj">
                <p>état : Bon état</p>
            </div>
            <div class="vendeurObj">
                <p>Vendeur : Alpha-Lin</p>
            </div>
            <div class="descObj">
                <p>Description : Canapé-lit pratique pour les fêtes.</p>
            </div>
        </div>
        <div class="produit">
        <div class="imgObj">
            <p>IMAGE</p>
        </div>
        <div class="titreObj">
            <a href="#"><h2>Canapé-lit</h2></a>
        </div>
        <div class="prixObj">
            <p>Prix : 472.67</p>
        </div>
        <div class="etatObj">
            <p>état : Bon état</p>
        </div>
        <div class="vendeurObj">
            <p>Vendeur : Alpha-Lin</p>
        </div>
        <div class="descObj">
            <p>Description : Canapé-lit pratique pour les fêtes.</p>
        </div>
    </div>
    <div class="produit">
    <div class="imgObj">
        <p>IMAGE</p>
    </div>
    <div class="titreObj">
        <a href="#"><h2>Canapé-lit</h2></a>
    </div>
    <div class="prixObj">
        <p>Prix : 472.67</p>
    </div>
    <div class="etatObj">
        <p>état : Bon état</p>
    </div>
    <div class="vendeurObj">
        <p>Vendeur : Alpha-Lin</p>
    </div>
    <div class="descObj">
        <p>Description : Canapé-lit pratique pour les fêtes.</p>
    </div>
</div>
<div class="produit">
<div class="imgObj">
    <p>IMAGE</p>
</div>
<div class="titreObj">
    <a href="#"><h2>Canapé-lit</h2></a>
</div>
<div class="prixObj">
    <p>Prix : 472.67</p>
</div>
<div class="etatObj">
    <p>état : Bon état</p>
</div>
<div class="vendeurObj">
    <p>Vendeur : Alpha-Lin</p>
</div>
<div class="descObj">
    <p>Description : Canapé-lit pratique pour les fêtes.</p>
</div>
</div>
<div class="produit">
<div class="imgObj">
    <p>IMAGE</p>
</div>
<div class="titreObj">
    <a href="#"><h2>Canapé-lit</h2></a>
</div>
<div class="prixObj">
    <p>Prix : 472.67</p>
</div>
<div class="etatObj">
    <p>état : Bon état</p>
</div>
<div class="vendeurObj">
    <p>Vendeur : Alpha-Lin</p>
</div>
<div class="descObj">
    <p>Description : Canapé-lit pratique pour les fêtes.</p>
</div>
</div>
        </div>    
    </div>
    ';

require 'html/footer.html';

?>
