<link rel="stylesheet" href="css/panier.css">

<?php
    if(!isset($_SESSION['id']))
        header('location: ?i=Compte');

    if(isset($_GET['del'])){
        $req = $bdd->prepare('DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?');
        $req->execute(array($_SESSION['id'],
                            $_GET['del']));
    }else if(isset($_GET['add'])){
        $req = $bdd->prepare('SELECT saleStatus FROM products WHERE id = ?');
        $req->execute(array($_GET['add']));

        if($req->fetch(PDO::FETCH_COLUMN) == 0){ // Vérifie que le produit n'est pas déjà acheté
            $req = $bdd->prepare('INSERT INTO shoppingCart(clientID, productID) VALUES(?, ?)');
            $req->execute(array($_SESSION['id'],
                                $_GET['add']));
        }
    }

    // Panier

    $req = $bdd->prepare('SELECT id, name, marketPosition, description, sellerID FROM products JOIN shoppingCart ON id = productID WHERE clientID = ?');
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
                    <input type="hidden" name="products[]" value="' . $produit['id'] . '">
                    <a href="?i=product&id=' . $produit['id'] . '">
                        <img class="articleImage" src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '" alt="Article picture" width="100">
                    </a>
                    <div>
                    <h3 class="articleName"><a href="?i=product&id=' . $produit['id'] . '">' . htmlspecialchars($produit['name']) . '</a></h3>
                    
                        <ul>
                            <li>
                                <p>
                                    <span class="inBold"><a href="?i=otherUser&id=' . $produit['sellerID'] . '" class="vendeur">' . htmlspecialchars($vendorInfos['pseudo']) . '</a></span> <span class="seller"></span>';

                reputationStars($vendorInfos['reputation']);

                echo           '</p>
                            </li>
                            <li>
                                <p class="descriptionArticle">' . htmlspecialchars($produit['description']) . '</p>
                            </li>
                        </ul>
                    </div>
                    <p class="price">' . number_format(reloadExternalPrices($produit, $temps), 2, '.', '') . '€</p>
                    <button type="button" onclick="location.href=\'?i=paiement&products[]=' . $produit['id'] . '\'">Acheter</button>
                    <p><a href="?i=panier&del=' . $produit['id'] . '">Retirer du Panier</a></p>
                </div>';
        }

        echo '<input type="hidden" name="i" value="paiement">
              <input type="submit" value="Tout acheter">
        </form>';
    }

    // Commandes

    $req = $bdd->prepare('SELECT purchases.id, products.id AS productID, users.id AS vendorID, pseudo, reputation, name FROM purchases JOIN products ON productID = products.id JOIN users ON users.id = products.sellerID WHERE buyerID = ?');
    $req->execute(array($_SESSION['id']));

    $purchases_research = $req->fetchAll(PDO::FETCH_ASSOC);

    echo '</div>
    <div id="orderContainer">
        <h2>Mes commandes</h2>';

    if(!empty($purchases_research)){
        require_once 'php/modules/etoile.php';

        echo '<div id="ordersContainer">';

        $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

        foreach ($purchases_research as $purchase) {
            $req_main_picture->execute(array($purchase['productID']));

            echo '<div id="container">
                    <p class="inBold purchaseFirstColumn">Commande n°' . $purchase['id'] . '<span class="numberOrder"></span></p>
                    <ul class="purchaseFirstColumn">
                        <li>
                            <p><span class="inBold">Vendeur : <a href="?i=otherUser&id=' . $purchase['vendorID'] . '" class="vendeur">' . htmlspecialchars($purchase['pseudo']) . '</a></span> <span class="seller"></span>';

            reputationStars($purchase['reputation']);

            echo           '</p>
                        </li>
                        <li>
                            <p><span class="inBold">Article:</span> <span class="listArticleInOrder">' . htmlspecialchars($purchase['name']) . '</span>

                        </li>
                    </ul>
                    <div class="purchaseSecondColumn">
                        <img src="images/products/' . htmlspecialchars($req_main_picture->fetch(PDO::FETCH_COLUMN)) . '" alt="Image de ' . htmlspecialchars($purchase['name']) . '">
                    </div>
                </div>';
        }

        echo '</div>';
    }

    echo '</div>';
?>
