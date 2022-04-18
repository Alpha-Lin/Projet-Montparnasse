<?php
    $req = $bdd->prepare('SELECT * FROM products JOIN shoppingCart ON id = productID WHERE clientID = ?');
    $req->execute(array($_SESSION['id']));

    $produits_research = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '<div id="topPage">
            <h2>Mon panier</h2>';

    if(!empty($produits_research))
    {
        echo '<div id="articles">';

        require 'php/modules/price.php';

        $req_pseudo_vendeur->execute(array($produit['sellerID']));
        $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

        $temps = time();

        foreach ($produits_research as $produit) {
            echo '<div class="article">
                    <h3 class="articleName">' . htmlspecialchars($produit['name']) . '</h3>
                    <img class="articleImage" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '" alt="Article picture">
                    <ul>
                        <li>
                            <p>
                                <span class="inBold">' . htmlspecialchars($req_pseudo_vendeur->fetch(PDO::FETCH_COLUMN)) . '</span> <span class="seller"></span>
                                <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                    <img alt="Stars" src="" id="starsBackgroundArticle">
                                    <img alt="Stars Foreground" src="" id="starsForegroundArticle">
                            </p>
                        </li>
                        <li>
                            <p class="descriptionArticle">' . htmlspecialchars($produit['description']) . '</p>
                        </li>
                    </ul>
                    <p><span class="price">10</span> <span class="currencyPrice">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</span></p>
                    <p class="delivery">Livraison prévu le: <span id=date></span></p>
                    <p><a href="inserer">Retirer du Panier</a></p>
                    <p><a href="inserer">Afficher</a></p>
                </div>';
        }

        echo '<button id="buyButton">Tout acheter</button>
        </div>';
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
                        <li>
                            <p><span class="inBold">Livraison:</span> <span id="deliveryLocation"></span></p>
                        
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
