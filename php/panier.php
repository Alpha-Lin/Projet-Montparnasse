<link rel="stylesheet" href="css/panier.css">

<?php
    if(!isset($_SESSION['id']))
        header('location: ?i=Compte');

    if(isset($_GET['del'])){
        $req = $bdd->prepare('DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?');
        $req->execute(array($_SESSION['id'],
                            $_GET['del']));
    }else if(isset($_GET['add'])){
        $req = $bdd->prepare('INSERT INTO shoppingCart(clientID, productID) VALUES(?, ?)');
        $req->execute(array($_SESSION['id'],
                            $_GET['add']));
    }

    $req = $bdd->prepare('SELECT * FROM products JOIN shoppingCart ON id = productID WHERE clientID = ?');
    $req->execute(array($_SESSION['id']));

    $produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div id="topPage">
            <h2>Mon panier</h2>';

    if(!empty($produits_research))
    {
        require 'php/modules/etoile.php';

        echo '<form id="articles">';

        require 'php/modules/price.php';

        $req_pseudo_reputation_vendeur = $bdd->prepare('SELECT pseudo, reputation FROM users WHERE id = ?');
        $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

        $temps = time();

        foreach ($produits_research as $produit) {
            $req_pseudo_reputation_vendeur->execute(array($produit['sellerID']));
            $req_main_picture->execute(array($produit['id']));

            $vendorInfos = $req_pseudo_reputation_vendeur->fetch(PDO::FETCH_ASSOC);

            echo '<div class="article">
                    <input type="hidden" name="paiement[]" value="' . $produit['id'] . '">
                    <a href="?i=product&id=' . $produit['id'] . '">
                        <img class="articleImage" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '" alt="Article picture" width="100">
                    </a>
                    <div>
                    <h3 class="articleName"><a href="?i=product&id=' . $produit['id'] . '">' . htmlspecialchars($produit['name']) . '</a></h3>
                    
                        <ul>
                            <li>
                                <p>
                                    <span class="inBold"><a href="#" class="vendeur">' . htmlspecialchars($vendorInfos['pseudo']) . '</a></span> <span class="seller"></span>';

                reputationStars($vendorInfos['reputation']);

                echo           '</p>
                            </li>
                            <li>
                                <p class="descriptionArticle">' . htmlspecialchars($produit['description']) . '</p>
                            </li>
                        </ul>
                    </div>
                    <p class="price">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                    <button onClick="location.href=\'?paiement=' . $produit['id'] . '\'">Acheter</button>
                    <p><a href="?i=panier&del=' . $produit['id'] . '">Retirer du Panier</a></p>
                </div>';
        }

        echo '<input type="submit" value="Tout acheter">
        </form>';
    }

    // pas fini
    $req = $bdd->prepare('SELECT purchases.id, pseudo FROM purchases JOIN products ON productID = products.id JOIN users ON users.id = products.sellerID WHERE buyerID = ?');
    $req->execute(array($_SESSION['id']));

    $purchases_research = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '</div>
    <div id="orderContainer">
        <h2>Mes commandes</h2>';

    if(!empty($purchases_research)){
        echo '<div id="ordersContainer">';

        foreach ($purchases_research as $purchase) {
            echo '<div id="container">
                    <p class="inBold">Commande n°' . $purchase['id'] . '<span class="numberOrder"></span></p>
                    <ul>
                        <li>
                            <p><span class="inBold">Vendeur : ' . htmlspecialchars($purchase['pseudo']) . '</span> <span class="seller"></span>
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                            <img alt="Stars" src="" id="starsBackgroundArticle">
                            <img alt="Stars Foreground" src="" id="starsForegroundArticle">
                            </p>
                        </li>
                        <li>
                            <p><span class="inBold">Article(s):</span> <span class="listArticleInOrder">Truc 1, truc2</span>

                        </li>
                    </ul>
                </div>
                <p class="toCenter">
                    <a id="arrowBtnArticle" href="" >
                        <i class="fa fa-arrow-circle-down" aria-hidden="true"></i>
                    </a >
                </p>';
        }

        echo '</div>';
    }

    echo '</div>';
?>
