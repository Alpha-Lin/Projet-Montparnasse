<link rel="stylesheet" href="/css/addresses_and_bankCards.css">

<?php
if(isset($_POST['products'], $_POST['deliveringAddress'], $_POST['billingAddress'], $_POST['bankCard'])){
    // Requêtes get infos
    $getProductName = $bdd->prepare("SELECT name FROM products WHERE id = ?");
    $buy_request = $bdd->prepare("INSERT INTO purchases(buyerID, productID, deliveringAddressID, billingAddressID, bankCardID) VALUES (?, ?, ?, ?, ?)");
    $change_product_state = $bdd->prepare("UPDATE products SET saleStatus = 1 WHERE id = ?");

    // Requêtes vérifications
    $verifProduitPanier = $bdd->prepare("SELECT * FROM shoppingCart WHERE clientID = ? AND productID = ?");
    $verifCarteClient = $bdd->prepare("SELECT * FROM bankCards WHERE clientID = ? AND id = ?");
    $verifAdresseClient = $bdd->prepare("SELECT * FROM addressBelongTo WHERE userID = ? AND addressID = ?");

    // Requêtes modifications des stats
    $deleteFromShoppingCart = $bdd->prepare("DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?");
    $increaseSalesVendor = $bdd->prepare("UPDATE users JOIN products ON users.id = sellerID SET sales = sales + 1 WHERE products.id = ?");
    $increasePurchasesClient = $bdd->prepare("UPDATE users SET purchases = purchases + 1 WHERE id = ?");

    $success = false;

    foreach ($_POST['products'] as $product_id) {
        $verifProduitPanier->execute(array($_SESSION['id'], // Vérifie que l'utilisateur a bien ce produit dans son panier
                                           $product_id));
        $verifCarteClient->execute(array($_SESSION['id'],   // Vérifie que la carte bancaire est bien celle de l'utilisateur
                                         $_POST['bankCard']));
        $verifAdresseClient->execute(array($_SESSION['id'], // Vérifie que l'adresse de livraison est bien celle de l'utilisateur
                                           $_POST['deliveringAddress']));

        $verifDeliveringAddress = !empty($verifAdresseClient->fetch());

        $verifAdresseClient->execute(array($_SESSION['id'], // Vérifie que l'adresse de facturation est bien celle de l'utilisateur
                                           $_POST['billingAddress']));

        if(!empty($verifProduitPanier->fetch()) && !empty($verifCarteClient->fetch()) && !empty($verifAdresseClient->fetch()) && $verifAdresseClient){
            $getProductName->execute(array($product_id));

            $buy_request->execute(array($_SESSION['id'],
                            $product_id,
                            $_POST['deliveringAddress'],
                            $_POST['billingAddress'],
                            $_POST['bankCard']));

            if(true){ //TODO : vérifier pas d'erreur
                $change_product_state->execute(array($product_id));
                echo '<p>Article "' . htmlspecialchars($getProductName->fetch(PDO::FETCH_COLUMN)) . '" acheté.</p>';

                $deleteFromShoppingCart->execute(array($_SESSION['id'],
                                                        $product_id));

                $increaseSalesVendor->execute(array($product_id));
                $increasePurchasesClient->execute(array($_SESSION['id']));

                $success = true;
            }
        }
    }

    if($success)
        echo '<link rel="stylesheet" href="css/success_paiement.css">';
    else
        header('location: ?i=panier');
}else if(isset($_GET['products'])){
    $req = $bdd->prepare("SELECT * FROM addresses INNER JOIN addressBelongTo ON id = addressID WHERE userID = ?");
    $req->execute(array($_SESSION['id']));
    $addresses = $req->fetchAll(PDO::FETCH_ASSOC);

    $req = $bdd->prepare("SELECT id, number, expirationDate, cvc, ownerName FROM bankCards WHERE clientID = ?");
    $req->execute(array($_SESSION['id']));
    $cards = $req->fetchAll(PDO::FETCH_ASSOC);

    if(empty($addresses) || empty($cards))
        echo '<h3>Veuillez remplir les informations nécessaires manquantes dans votre <a href="?i=Compte">profil</a>.</h3>
                <ul>
                    <li>Adresse</li>
                    <li>Carte banquaire</li>
                </ul>';
    else
        require 'php/modules/paiement_interface.php';
}
    
else
    require 'php/panier.php';

?>
