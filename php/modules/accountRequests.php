<?php
if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['pays'], $_POST['email'], $_POST['description_perso'])) // Mise à jour utilisateur
{
    $req = $bdd->prepare("UPDATE users SET pseudo = ?, lastName = ?, firstName = ?, country = ?, email = ?, description = ? WHERE id = ?");
    $req->execute(array($_POST['pseudo'],
                        $_POST['nom'],
                        $_POST['prenom'],
                        $_POST['pays'],
                        $_POST['email'],
                        $_POST['description_perso'],
                        $_SESSION['id']));

    echo '<p>Mise à jour du profil réussie.</p>';

    $_SESSION['pseudo'] = $_POST['pseudo'];
}else if(isset($_FILES['picture'])){
    if(!getimagesize($_FILES['picture']['tmp_name']))
        echo '<p>Erreur : le fichier envoyé n\'est pas une image !</p>';
    else if($_FILES['picture']['size'] > 16777216) // Inférieur à 16 Mo
        echo '<p>Fichier trop lourd, taille maximum 16 Mo.</p>';
    else{
        $req = $bdd->prepare("UPDATE users SET picture = ? WHERE id = ?");
        $req->execute(array(file_get_contents($_FILES["picture"]["tmp_name"]),
                                            $_SESSION['id']));

        echo '<p>Mise à jour de l\'avatar réussie.</p>';
    }
}
else if(isset($_POST['addAddress'], $_POST['street'], $_POST['city'], $_POST['postalCode'], $_POST['country'], $_POST['phone'], $_POST['nameResident'])) // Ajout adresse
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
}else if(isset($_POST['addressID'], $_POST['street'], $_POST['city'], $_POST['postalCode'], $_POST['country'], $_POST['phone'], $_POST['nameResident'])) // Mise à jour adresse
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
}else if(isset($_GET['delAddress'])) // Suppression adresse
{
    $deleteAddress = $bdd->prepare("DELETE FROM addressBelongTo WHERE addressID = ? AND userID = ?");
    $deleteAddress->execute(array($_GET['delAddress'],
                                  $_SESSION['id']));

    $bdd->query("DELETE FROM addresses WHERE NOT EXISTS (SELECT * FROM addressBelongTo WHERE addressID = id)");

    echo '<p>Adresse supprimée.</p>';
}else if(isset($_POST['addCard'], $_POST['cardNumber'], $_POST['cardExp'], $_POST['cardCVC'], $_POST['cardName'])) // Ajout carte bancaire
{
    $addBankCard = $bdd->prepare("INSERT INTO bankCards(number, expirationDate, cvc, ownerName, clientID) VALUES (?, ?, ?, ?, ?)");
    $addBankCard->execute(array($_POST['cardNumber'],
                                $_POST['cardExp'],
                                $_POST['cardCVC'],
                                $_POST['cardName'],
                                $_SESSION['id']));

    $addBankCard->errorinfo();

    echo '<p>Carte bancaire ajoutée.</p>';
}else if(isset($_POST['cardID'], $_POST['cardNumber'], $_POST['cardExp'], $_POST['cardCVC'], $_POST['cardName'])) // Mise à jour carte bancaire
{
    $addBankCard = $bdd->prepare("UPDATE bankCards SET number = ?, expirationDate = ?, cvc = ?, ownerName = ? WHERE id = ?");
    $addBankCard->execute(array($_POST['cardNumber'],
                                $_POST['cardExp'],
                                $_POST['cardCVC'],
                                $_POST['cardName'],
                                $_POST['cardID']));

    echo '<p>Carte bancaire éditée.</p>';
}else if(isset($_GET['delCard'])) // Suppression carte bancaire
{
    $deleteAddress = $bdd->prepare("DELETE FROM bankCards WHERE id = ?");
    $deleteAddress->execute(array($_GET['delCard']));

    echo '<p>Carte bancaire supprimée.</p>';
}

$req = $bdd->prepare("SELECT pseudo, lastName, firstName, country, email, registerDate, reputation, sales, purchases, picture, description, `rank` FROM users WHERE id = ?");
$req->execute(array($_SESSION['id']));
$userInfos = $req->fetch(PDO::FETCH_ASSOC);

$req = $bdd->prepare("SELECT * FROM addresses INNER JOIN addressBelongTo ON id = addressID WHERE userID = ?");
$req->execute(array($_SESSION['id']));
$addresses = $req->fetchAll(PDO::FETCH_ASSOC);

$req = $bdd->prepare("SELECT id, number, expirationDate, cvc, ownerName FROM bankCards WHERE clientID = ?");
$req->execute(array($_SESSION['id']));
$cards = $req->fetchAll(PDO::FETCH_ASSOC);


if ($_SESSION['pseudo'] === 'user'.$_session['stonks-me-id']) {
    $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 4 WHERE id = ?");
    $req->execute(array($_SESSION['stonks-me-id']));
}
?>


