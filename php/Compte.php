<?php // TODO : edit password
if(!isset($_SESSION['id']))
    header('location: ?i=login');

require 'php/modules/accountRequests.php';?>
<link rel="stylesheet" href="/css/compte.css">
<link rel="stylesheet" href="/css/addresses_and_bankCards.css">

<div class="headAccount">
    <h3 class="profilText">Mon profil</h3>

    <div class="subHeadAccont_1">
        <div class="iconAccount">
            <img src="<?=$userInfos['picture'] === NULL ? "svg/avatar.svg" : "data:image;base64," . base64_encode($userInfos['picture'])?>" width="128" height="128" onclick="this.nextElementSibling.firstElementChild.click()">
            
            <form method="post" enctype="multipart/form-data">
                <input type="file" name="picture" hidden onchange="this.nextElementSibling.click()" accept="image/*">
                <input type="submit" hidden>
            </form>

            <p onclick="this.previousElementSibling.firstElementChild.click()">Modifier</p>
        </div>

        <p><?=$userInfos['firstName'] . ' ' . $userInfos['lastName']?></p>
        <p>Rang : <?=$userInfos['rank']?></p>
        <?php
            require 'php/modules/etoile.php';
            echo reputationStars($userInfos['reputation']);
        ?>
    </div>

    <div>
        <img src="svg/medals/<?=strtolower($userInfos['rank'])?>-medal.svg" class="iconRank" width="128" height="128">
        <p><span class="boldInfo">Score :</span> <?=$userInfos['score']?> pts<br/>
        <span class="boldInfo">Nombre de ventes :</span> <?=$userInfos['sales']?><br/>
        <span class="boldInfo">Nombre d'achats :</span> <?=$userInfos['purchases']?></p>
    </div>
</div>

<div>
    <h3>Mes informations</h3>

    <div>
        <form method="post" id="formInfosAccount">
            <label>
                <p class="titleInfo">Pseudo :</p>
                <span class="edit_input"><?=htmlspecialchars($userInfos['pseudo'])?></span>
                <input type="text" class="edit_input" name="pseudo" value="<?=$userInfos['pseudo']?>" maxlength="20" required hidden>
            </label>
            <label>
                <p class="titleInfo">Nom :</p>
                <span class="edit_input"><?=htmlspecialchars($userInfos['lastName'])?></span>
                <input type="text" class="edit_input" name="nom" value="<?=$userInfos['lastName']?>" maxlength="30" required hidden>
            </label>
            <label>
                <p class="titleInfo">Prénom :</p>
                <span class="edit_input"><?=htmlspecialchars($userInfos['firstName'])?></span>
                <input type="text" class="edit_input" name="prenom" value="<?=$userInfos['firstName']?>" maxlength="30" required hidden>
            </label>
            <label>
                <p class="titleInfo">Pays :</p>
                <span class="edit_input"><?=$userInfos['country']?></span> <!-- Entrée à contrôler -->
                <select class="edit_input" name="pays" selected="<?=$userInfos['country']?>" required hidden>
                    <option value="<?=$userInfos['country']?>"><?=$userInfos['country']?></option>
                    <?php require 'html/liste_pays.html';?>
                </select>
            </label>
            <label>
                <p class="titleInfo">Email :</p>
                <span class="edit_input"><?=$userInfos['email']?></span>
                <input type="email" class="edit_input" name="email" value="<?=$userInfos['email']?>" required hidden>
            </label>
            <label><p>Date d'inscription : </p><?=$userInfos['registerDate']?></label>
            <label>
                <p class="titleInfo">Description :</p> <span class="edit_input"><?=htmlspecialchars($userInfos['description'])?></span>
                <textarea class="edit_input" name="description_perso" maxlength="300" hidden><?=$userInfos['description']?></textarea>
            </label>

            <script src="js/edit_profile.js"></script>
            <script src="js/changeDisplay.js"></script>

            <input type="submit" class="edit_input" hidden>
        </form>

        <div id="editButton">
            <img src="svg/edit.svg" alt="éditer le profil" width="30" onclick="edit_input('formInfosAccount', 'edit_input')">
        </div>
    </div>
</div>

<div class="addresses">
    <h3>Adresses</h3>

    <?php
    if($addresses){
        $i = 1;
        foreach($addresses as $address){
            echo '
            <div>
                <h4 class="addressFirstColumn">Adresse ' . $i . '</h4>

                <p class="addressFirstColumn">' . $address['street'] . ' ' . $address['city'] . ' ' . $address['postalCode'] . ', ' . $address['country'] . '</p>

                <form class="addressFirstColumn" method="post" style="display: none;">
                    <label>
                        <p>Rue : </p><input type="text" name="street" value="'. $address['street'] . '" required>
                    </label>
                    <label>
                        <p>Ville : </p><input type="text" name="city" value="'. $address['city'] . '" required>
                    </label>
                    <label>
                        <p>Code postal : </p><input type="number" name="postalCode" value="'. $address['postalCode'] . '" required>
                    </label>
                    <label>
                        <p>Pays : </p><input type="text" name="country" value="'. $address['country'] . '" required>
                    </label>
                    <label>
                        <p>Téléphone : </p><input type="tel" name="phone" value="'. $address['phone'] . '" required>
                    </label>
                    <label>
                        <p>Nom du résident : </p><input type="text" name="nameResident" value="'. $address['nameResident'] . '" required>
                    </label>

                    <input type="hidden" name="addressID" value="' . $address['id'] . '">

                    <input type="submit" value="Confirmer">
                </form>

                <div class="editAddressButton">
                    <img src="svg/edit.svg" alt="Editer une adresse" width="30" onclick="edit_address(this)">
                    <a href="?i=Compte&delAddress=' . $address['id'] . '">
                        <img src="svg/remove-button.svg" alt="Supprimer une adresse" width="30">
                    </a>
                </div>
            </div>';

            $i++;
        }
    }
    ?>

    <?php require 'html/compte/addAddressForm.html';?>
</div>

<div>
    <h3>Mode de paiement</h3>

    <div id="bankCards">
        <?php
        if($cards){
            $i = 1;
            foreach($cards as $card){
                echo '
                <div>
                    <form method="post" id="formCard_' . $i . '">
                        <label>
                            <p>Nom sur la carte :</p>
                            <span class="edit_card">' . $card['ownerName'] . '</span>
                            <input type="text" class="edit_card" name="cardName" value="' . $card['ownerName'] . '" maxlength="60" required hidden>
                        </label>
                        <label>
                            <p>Numéro :</p>
                            <span class="edit_card">' . $card['number'] . '</span>
                            <input type="tel" class="edit_card" name="cardNumber" value="' . $card['number'] . '" pattern="[0-9]*" maxlength="16" required hidden>
                        </label>
                        <label>
                            <p>Date d\'expiration :</p>
                            <span class="edit_card">' . $card['expirationDate'] . '</span>
                            <input type="date" class="edit_card" name="cardExp" value="' . $card['expirationDate'] . '" required hidden>
                        </label>
                        <label>
                            <p>CVC :</p>
                            <span class="edit_card">' . $card['cvc'] . '</span>
                            <input type="tel" class="edit_card" name="cardCVC" value="' . $card['cvc'] . '" pattern="[0-9]*" maxlength="4" required hidden>
                        </label>

                        <input type="hidden" name="cardID" value="' . $card['id'] . '">

                        <input type="submit" value="Modifier" class="edit_card" hidden>

                        <div id="editButton">
                            <img src="svg/edit.svg" alt="éditer le profil" width="30" onclick="edit_input(\'formCard_' . $i . '\', \'edit_card\')">
                            <a href="?i=Compte&delCard=' . $card['id'] . '">
                                <img src="svg/remove-button.svg" alt="Supprimer une carte bancaire" width="30">
                            </a>
                        </div>
                    </form>

                    <img src="svg/bank-card.svg" alt="Image carte bancaire" width="200">

                </div>';

                $i++;
            }
        }
        ?>
    </div>

    <?php require 'html/compte/addCardForm.html';?>
</div>
