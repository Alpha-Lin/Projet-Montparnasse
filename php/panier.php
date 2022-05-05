<link rel="stylesheet" href="css/panier.css">

<?php
function alreadyNoted($pruchaseID){
    global $bdd;

    $req = $bdd->prepare('SELECT purchaseID FROM notes WHERE purchaseID = ?'); // Vérifie que l'utilisateur n'a pas déjà noté l'achat
    $req->execute(array($pruchaseID));

    return $req->fetch(PDO::FETCH_COLUMN);
}

    if(!isset($_SESSION['id']))
        header('location: ?i=Compte');

    if(isset($_GET['del'])){
        $req = $bdd->prepare('DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?');
        $req->execute(array($_SESSION['id'],
                            $_GET['del']));
    }else if(isset($_GET['add'])){
        $req = $bdd->prepare('SELECT id FROM products WHERE id = ? AND saleStatus = 0 AND sellerID <> ?');
        $req->execute(array($_GET['add']));

        if(!empty($req->fetch(PDO::FETCH_COLUMN))){ // Vérifie que le produit n'est pas déjà acheté et que l'utilisateur n'est pas le vendeur
            $req = $bdd->prepare('INSERT INTO shoppingCart(clientID, productID) VALUES (?, ?)');
            $req->execute(array($_SESSION['id'],
                                $_GET['add']));
        }
    }else if(isset($_GET['rateStars'], $_GET['purchaseID'])){ // Notations et avis
        $req = $bdd->prepare('SELECT id FROM purchases WHERE buyerID = ? AND id = ?'); // Vérifie que l'utilisateur a bien acheté le produit
        $req->execute(array($_SESSION['id'],
                            $_GET['purchaseID']));

        if($req->fetch(PDO::FETCH_COLUMN)){
            $req = $bdd->prepare('SELECT purchaseID FROM notes WHERE purchaseID = ?'); // Vérifie que l'utilisateur n'a pas déjà noté l'achat
            $req->execute(array($_GET['purchaseID']));

            if(!alreadyNoted($_GET['purchaseID'])){
                $req = $bdd->prepare('INSERT INTO notes(rating, comment, purchaseID) VALUES (?, ?, ?)'); // On inscrit la note et l'avis
                $req->execute(array($_GET['rateStars'],
                                    isset($_GET['rateComment']) ? $_GET['rateComment'] : '',
                                    $_GET['purchaseID']));

                $req = $bdd->prepare('UPDATE users JOIN products ON users.id = sellerID JOIN purchases ON products.id = productID SET reputation = (SELECT AVG(rating) FROM notes JOIN purchases ON purchaseID = purchases.id JOIN products ON productID = products.id WHERE sellerID = (SELECT users.id FROM users JOIN products ON users.id = sellerID JOIN purchases ON products.id = productID WHERE purchases.id = :purchase_id)) WHERE purchases.id = :purchase_id'); // On met à jour la réputation du vendeur (2H pour comprendre que WITH AS ne fonctionnait pas avec UPDATE... donc gros boudin)
                $req->execute(array('purchase_id' => $_GET['purchaseID']));

                echo '<p>Avis enregistré.</p>';
            }
        }
    }

    // Panier

    $req = $bdd->prepare('SELECT id, name, lastPrice, marketPosition, description, saleStatus, sellerID FROM products JOIN shoppingCart ON id = productID WHERE clientID = ?');
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
                                    <span class="inBold"><a href="?i=otherUser&id=' . $produit['sellerID'] . '" class="vendeur">' . htmlspecialchars($vendorInfos['pseudo']) . '</a></span> <span class="seller"></span>' .
                                    reputationStars($vendorInfos['reputation']) .
                               '</p>
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
        <h2>Historique de mes commandes</h2>';

    if(!empty($purchases_research)){
        require_once 'php/modules/etoile.php';

        echo '<script src="js/panier.js"></script>
              <div id="ordersContainer">';

        $req_main_picture = $bdd->prepare('SELECT fileName FROM pictures WHERE productID = ?');

        foreach ($purchases_research as $purchase) {
            $req_main_picture->execute(array($purchase['productID']));

            echo '<div id="container" onclick="rating(' . $purchase['id'] . ')">
                    <p class="inBold purchaseFirstColumn">Commande n°' . $purchase['id'] . '<span class="numberOrder"></span></p>
                    <ul class="purchaseFirstColumn">
                        <li>
                            <p><span class="inBold">Vendeur : <a href="?i=otherUser&id=' . $purchase['vendorID'] . '" class="vendeur">' . htmlspecialchars($purchase['pseudo']) . '</a></span> <span class="seller"></span>' .
            reputationStars($purchase['reputation']) .
                           '</p>
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

        // TODO si avis déjà fait

        $reqGetNotes = $bdd->prepare('SELECT rating, comment, releaseDate FROM notes WHERE purchaseID = ?');
        
        foreach ($purchases_research as $purchase) {
            if(alreadyNoted($purchase['id'])){
                $reqGetNotes->execute(array($purchase['id']));

                $infosNotes = $reqGetNotes->fetch(PDO::FETCH_ASSOC);

                echo   '<div id="rate_' . $purchase['id'] . '" class="rate" hidden>
                            <h2>Votre avis donné sur votre commande (n°' . $purchase['id'] . ') le ' . $infosNotes['releaseDate'] . '</h2>

                            <form id="formRate">
                                <label>
                                    Votre expérience avec votre commande :
                                    <textarea readonly>' . $infosNotes['comment'] . '</textarea>
                                </label>

                                <label>
                                    Votre note à votre commande :' .
                                    reputationStars($infosNotes['rating']) .
                               '</label>
                            </form>
                        </div>';
            }
            else
                echo   '<div id="rate_' . $purchase['id'] . '" class="rate" hidden>
                            <h2>Laisser un avis sur votre commande (n°' . $purchase['id'] . ')</h2>

                            <form id="formRate">
                                <input type="hidden" name="i" value="panier">
                                <input type="hidden" name="purchaseID" value="' . $purchase['id'] . '">

                                <label>
                                    Décrivez votre expérience avec votre commande :
                                    <textarea maxlength="1500" name="rateComment" placeholder="Type here"></textarea>
                                </label>

                                <label>
                                    Donnez une note à votre commande :
                                    <input type="number" step="0.1" min="0.1" max="5" name="rateStars" required>
                                    <i class="fa fa-star gold"></i>
                                </label>

                                <input type="submit" value="Envoyer l\'avis">
                            </form>
                        </div>';
        }
    }

    echo '</div>';
?>
