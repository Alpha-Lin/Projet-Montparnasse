<?php // TODO : edit password
if(!isset($_SESSION['id']))
    header('location: ?i=login');

if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['pays'], $_POST['email'], $_POST['description_perso']))
{
    if(!empty($_POST['pseudo']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pays']) && !empty($_POST['email']))
    {
        $req = $bdd->prepare("UPDATE users SET pseudo = ?, lastName = ?, firstName = ?, country = ?, email = ?, description = ? WHERE id = ?"); // va chercher le hash de l'utilisateur
        $req->execute(array($_POST['pseudo'],
                            $_POST['nom'],
                            $_POST['prenom'],
                            $_POST['pays'],
                            $_POST['email'],
                            $_POST['description_perso'],
                            $_SESSION['id']));

        echo '<p>Mise à jour du profil réussie.</p>';

        $_SESSION['pseudo'] = $_POST['pseudo'];
    }
}
else if(isset($_POST['addAddress'], $_POST['street'], $_POST['city'], $_POST['postalCode'], $_POST['country'], $_POST['phone'], $_POST['nameResident']))
{
    $insertAddress = $bdd->prepare("INSERT INTO addresses(street, city, postalCode, country, phone, nameResident) VALUES (?, ?, ?, ?, ?, ?)");
    $insertAddress->execute(array($_POST['street'],
                                  $_POST['city'],
                                  $_POST['postalCode'],
                                  $_POST['country'],
                                  $_POST['phone'],
                                  $_POST['nameResident']));

    $linkAddress = $bdd->prepare("INSERT INTO addressBelongTo(addressID, userID) VALUES (?, ?)");
    $linkAddress->execute(array($bdd->lastInsertId(),
                                       $_SESSION['id']));

    echo '<p>Adresse ajoutée.</p>';
}else if(isset($_POST['addressID'], $_POST['street'], $_POST['city'], $_POST['postalCode'], $_POST['country'], $_POST['phone'], $_POST['nameResident']))
{
    $editAddress = $bdd->prepare("UPDATE addresses SET street = ?, city = ?, postalCode = ?, country = ?, phone = ?, nameResident = ? WHERE id = ?");
    $editAddress->execute(array($_POST['street'],
                                $_POST['city'],
                                $_POST['postalCode'],
                                $_POST['country'],
                                $_POST['phone'],
                                $_POST['nameResident'],
                                $_POST['addressID']));

    echo '<p>Adresse éditée.</p>';
}


$req = $bdd->prepare("SELECT pseudo, lastName, firstName, country, email, registerDate, reputation, sales, purchases, description, rank, score FROM users WHERE id = ?");
$req->execute(array($_SESSION['id']));
$userInfos = $req->fetch(PDO::FETCH_ASSOC);

$req = $bdd->prepare("SELECT * FROM addresses INNER JOIN addressBelongTo ON id = addressID WHERE userID = ?");
$req->execute(array($_SESSION['id']));
$addresses = $req->fetchAll(PDO::FETCH_ASSOC);?>

<link rel="stylesheet" href="/css/compte.css">

<div class="headAccount">
    <h3 class="profilText">Mon profil</h3>

    <div class="subHeadAccont_1">
        <div class="iconAccount">
            <img width="128" height="128"><br>
            <a href="#">Modifier</a>
        </div>

        <p><?=$userInfos['firstName'] . ' ' . $userInfos['lastName']?></p>
        <p>Rang : <?=$userInfos['rank']?></p>
        <p>TODO Stars</p>
    </div>

    <div>
        <img class="iconRank" width="64" height="64">
        <p><span class="boldInfo">Score :</span> <?=$userInfos['score']?> pts<br/>
        <span class="boldInfo">Nombre de ventes :</span> <?=$userInfos['sales']?><br/>
        <span class="boldInfo">Nombre d'achats :</span> <?=$userInfos['purchases']?></p>
    </div>
</div>

<div>
    <h3>Mes informations</h3>

    <div>
        <form method="post" class="formInfosAccount">
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

            <input type="submit" class="edit_input" hidden>
        </form>

        <div id="editButton">
            <img src="svg/edit.svg" alt="éditer le profil" width="30" onclick="edit_profile()">
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
                </div>
            </div>';

            $i++;
        }
    }
    ?>

    <form id="addAddressForm" method="post" style="display: none;">
        <h4>Nouvelle adresse</h4>

        <label>
            <p>Rue : </p><input type="text" name="street" required>
        </label>
        <label>
            <p>Ville : </p><input type="text" name="city" required>
        </label>
        <label>
            <p>Code postal : </p><input type="number" name="postalCode" required>
        </label>
        <label>
            <p>Pays : </p><input type="text" name="country" required>
        </label>
        <label>
            <p>Téléphone : </p><input type="tel" name="phone" required>
        </label>
        <label>
            <p>Nom du résident : </p><input type="text" name="nameResident" required>
        </label>

        <input type="hidden" name="addAddress">

        <input type="submit" value="Ajouter">
    </form>

    <div id="addAddressButton">
        <img src="svg/add-button.svg" alt="Ajouter une adresse" width="30" onclick="add_address(this)">
    </div>
</div>
