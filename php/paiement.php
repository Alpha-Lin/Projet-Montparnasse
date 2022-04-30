<?php
if(isset($_POST['products'], $_POST['deliveringAddress'], $_POST['billingAddress'], $_POST['bankCard'])){
    // Requêtes get infos
    $getProductName = $bdd->prepare("SELECT name FROM products WHERE id = ?");
    $buy_request = $bdd->prepare("INSERT INTO purchases(buyerID, productID, deliveringAddressID, billingAddressID, bankCardID) VALUES (?, ?, ?, ?, ?)");
    $change_product_state = $bdd->prepare("UPDATE products SET saleStatus = 1 WHERE id = ?");
    $deleteFromShoppingCart = $bdd->prepare('DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?');

    //Requête vérification
    $verifProduitPanier = $bdd->prepare("SELECT * FROM shoppingCart WHERE clientID = ? AND productID = ?");
    $verifCarteClient = $bdd->prepare("SELECT * FROM bankCards WHERE clientID = ? AND id = ?");
    $verifAdresseClient = $bdd->prepare("SELECT * FROM addressBelongTo WHERE userID = ? AND addressID = ?");

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
                echo '<p>Article "' . htmlspecialchars($getProductName->fetch(PDO::FETCH_COLUMN)) . ' acheté.</p>';

                $deleteFromShoppingCart->execute(array($_SESSION['id'],
                                                        $product_id));
                $success = true;
            }
        }
    }

    if($success)
        echo '<link rel="stylesheet" href="css/success_paiement.css">';
    else
        header('location: ?i=panier');
}else if(isset($_GET['products']))
    require 'php/modules/paiement_interface.php';
else
    require 'php/panier.php';

?>
