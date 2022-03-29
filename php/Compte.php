<?php // TODO : edit password
if(!isset($_SESSION['id']))
    header('location: ?i=login');

if(isset($_POST['pseudo'], $_POST['nom'], $_POST['prenom'], $_POST['pays'], $_POST['email'], $_POST['description_perso']))
{
    if(!empty($_POST['pseudo']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['pays']) && !empty($_POST['email']))
    {
        $req = $bdd->prepare("UPDATE utilisateur SET pseudo = ?, nom = ?, prenom = ?, pays = ?, email = ?, description_perso = ? WHERE id = ?"); // va chercher le hash de l'utilisateur
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

$req = $bdd->prepare("SELECT pseudo, nom, prenom, pays, email, date_inscription, reputation, nb_vente, description_perso FROM utilisateur WHERE id = ?"); // va chercher le hash de l'utilisateur
$req->execute(array($_SESSION['id']));
$userInfos = $req->fetch(PDO::FETCH_ASSOC);?>

<link rel="stylesheet" href="/css/compte.css">

<div class="headAccount">
    <h3 class="profilText">Mon profil</h3>

    <div class="subHeadAccont_1">
        <div class="iconAccount">
            <img width="128" height="128"><br>
            <a href="#">Modifier</a>
        </div>

        <p>PRENOM NOM</p>
        <p>Membre : ?</p>
        <p>TODO Stars</p>
    </div>

    <div>
        <img class="iconRank" width="64" height="64">
        <p><span class="boldInfo">Score :</span> pts<br/>
        <span class="boldInfo">Nombre de ventes :</span><br/>
        <span class="boldInfo">Nombre d'achats :</span></p>
    </div>
</div>

<div class="infosAccount">
    <h3>Mes informations</h3>

    <div>
        <form method="post" class="formInfosAccount">
            <label>
                <p class="titleInfo">Pseudo :</p>
                <span class="edit_input"><?=$userInfos['pseudo']?></span>
                <input type="text" class="edit_input" name="pseudo" value="<?=$userInfos['pseudo']?>" maxlength="20" required hidden>
            </label>
            <label>
                <p class="titleInfo">Nom :</p>
                <span class="edit_input"><?=$userInfos['nom']?></span>
                <input type="text" class="edit_input" name="nom" value="<?=$userInfos['nom']?>" maxlength="30" required hidden>
            </label>
            <label>
                <p class="titleInfo">Prénom :</p>
                <span class="edit_input"><?=$userInfos['prenom']?></span>
                <input type="text" class="edit_input" name="prenom" value="<?=$userInfos['prenom']?>" maxlength="30" required hidden>
            </label>
            <label>
                <p class="titleInfo">Pays :</p>
                <span class="edit_input"><?=$userInfos['pays']?></span>
                <select class="edit_input" name="pays" selected="<?=$userInfos['pays']?>" required hidden>
                    <option value="<?=$userInfos['pays']?>"><?=$userInfos['pays']?></option>
                    <?php require 'html/liste_pays.html';?>
                </select>
            </label>
            <label>
                <p class="titleInfo">Email :</p>
                <span class="edit_input"><?=$userInfos['email']?></span>
                <input type="email" class="edit_input" name="email" value="<?=$userInfos['email']?>" required hidden>
            </label>
            <label><p>Date d'inscription : </p><?=$userInfos['date_inscription']?></label>
            <label>
                <p class="titleInfo">Description :</p> <?=htmlspecialchars($userInfos['description_perso'])?>
                <textarea class="edit_input" name="description_perso" maxlength="300" hidden><?=$userInfos['description_perso']?></textarea>
            </label>

            <script src="js/edit_profile.js"></script>

            <input type="submit" class="edit_input" hidden>
        </form>

        <div id="editButton">
            <img src="images/edit.svg" alt="éditer le profil" width="30" onclick="edit_profile()" id="edit_button">
        </div>
    </div>
</div>
