<link rel="stylesheet" href="/css/addresses_and_bankCards.css">
<script src="js/choose_paiement.js"></script>

<form action="?i=paiement" method="post">
    <div>
        <h3>Mode de paiement</h3>

        <div id="bankCards">
            <?php
            $req = $bdd->prepare("SELECT * FROM addresses INNER JOIN addressBelongTo ON id = addressID WHERE userID = ?");
            $req->execute(array($_SESSION['id']));
            $addresses = $req->fetchAll(PDO::FETCH_ASSOC);

            $req = $bdd->prepare("SELECT id, number, expirationDate, cvc, ownerName FROM bankCards WHERE clientID = ?");
            $req->execute(array($_SESSION['id']));
            $cards = $req->fetchAll(PDO::FETCH_ASSOC);

            if($cards){
                $i = 1;
                foreach($cards as $card){
                    echo '
                    <div onclick="this.lastElementChild.click()">
                        <div>
                            <label>
                                <p>Nom sur la carte :</p>
                                <span class="edit_card">' . $card['ownerName'] . '</span>
                            </label>
                            <label>
                                <p>Numéro :</p>
                                <span class="edit_card">' . $card['number'] . '</span>
                            </label>
                            <label>
                                <p>Date d\'expiration :</p>
                                <span class="edit_card">' . $card['expirationDate'] . '</span>
                            </label>
                            <label>
                                <p>CVC :</p>
                                <span class="edit_card">' . $card['cvc'] . '</span>
                            </label>

                            <input type="hidden" name="cardID" value="' . $card['id'] . '">

                            <input type="submit" value="Modifier" class="edit_card" hidden>
                        </div>

                        <img src="svg/bank-card.svg" alt="Image carte bancaire" width="200">

                        <input type="radio" name="bankCard" hidden value="' . $card['id'] . '" onclick="choose_paiement(this, \'bankCard\')" required>
                    </div>';

                    $i++;
                }
            }
            ?>
        </div>
    </div>

    <div class="addresses">
        <h3>Adresse de livraison :</h3>

        <div id="bankCards">
            <?php
            if($addresses){
                $i = 1;
                foreach($addresses as $address){
                    echo '
                    <div onclick="this.lastElementChild.click()">
                        <h4 class="addressFirstColumn">Adresse ' . $i . '</h4>

                        <p class="addressFirstColumn">' . $address['street'] . ' ' . $address['city'] . ' ' . $address['postalCode'] . ', ' . $address['country'] . '</p>
                        <input type="radio" name="deliveringAddress" hidden value="' . $address['id'] . '" onclick="choose_paiement(this, \'deliveringAddress\')" required>
                    </div>';

                    $i++;
                }
            }
            ?>
        </div>
    </div>

    <div class="addresses">
        <h3>Adresse de facuration :</h3>

        <div id="bankCards">
            <?php
            if($addresses){
                $i = 1;
                foreach($addresses as $address){
                    echo '<div onclick="this.lastElementChild.click()">
                            <h4 class="addressFirstColumn">Adresse ' . $i . '</h4>

                            <p class="addressFirstColumn">' . $address['street'] . ' ' . $address['city'] . ' ' . $address['postalCode'] . ', ' . $address['country'] . '</p>
                            <input type="radio" name="billingAddress" hidden value="' . $address['id'] . '" onclick="choose_paiement(this, \'billingAddress\')" required>
                        </div>';

                    $i++;
                }
            }
            ?>
        </div>
    </div>

    <div>
        <?php
            foreach ($_GET['products'] as $product_id) {
                echo '<input type="hidden" name="products[]" value="' . $product_id . '">';
            }
        ?>
        <input type="submit" value="Payer" id="paid">
    </div>
</form>
