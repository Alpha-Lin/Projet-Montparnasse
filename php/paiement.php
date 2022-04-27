<?php
$req = $bdd->prepare("SELECT * FROM products WHERE id = ?");
if(isset($_POST['products'], $_POST['deliveringAddress'], $_POST['billingAddress'], $_POST['year'], $_POST['bankCard'])){
    $getProductName = $bdd->prepare("SELECT name FROM products WHERE id = ?");
    $buy_request = $bdd->prepare("INSERT INTO purchases(buyerID, productID, deliveringAddressID, billingAddressID, bankCardID) VALUES (?, ?, ?, ?, ?)");
    $change_product_state = $bdd->prepare("UPDATE products SET saleStatus = 1 WHERE id = ?");
    $deleteFromShoppingCart = $bdd->prepare('DELETE FROM shoppingCart WHERE clientID = ? AND productID = ?');

    foreach ($_POST['products'] as $product_id) {
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
        }
    }
}else
    require 'php/modules/paiement_interface.php';

?>
