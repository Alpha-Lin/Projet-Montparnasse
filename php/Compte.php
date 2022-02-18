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

<form method="post">
    <ul>
        <li><label>
            Pseudo : <span class="edit_input"><?=$userInfos['pseudo']?></span>
            <input type="text" class="edit_input" name="pseudo" value="<?=$userInfos['pseudo']?>" maxlength="20" required hidden>
        </label></li>
        <li><label>
            Nom : <span class="edit_input"><?=$userInfos['nom']?></span>
            <input type="text" class="edit_input" name="nom" value="<?=$userInfos['nom']?>" maxlength="30" required hidden>
        </label></li>
        <li><label>
            Prénom : <span class="edit_input"><?=$userInfos['prenom']?></span>
            <input type="text" class="edit_input" name="prenom" value="<?=$userInfos['prenom']?>" maxlength="30" required hidden>
        </label></li>
        <li><label>
            Pays : <span class="edit_input"><?=$userInfos['pays']?></span>
            <select class="edit_input" name="pays" selected="<?=$userInfos['pays']?>" required hidden>
                <option value="<?=$userInfos['pays']?>"><?=$userInfos['pays']?></option>
                <?php require 'html/liste_pays.html';?>
            </select>
        </label></li>
        <li><label>
            Email : <span class="edit_input"><?=$userInfos['email']?></span>
            <input type="email" class="edit_input" name="email" value="<?=$userInfos['email']?>" required hidden>
        </label></li>
        <li>Date d'inscription : <?=$userInfos['date_inscription']?></li>
        <li>Réputation : <?=$userInfos['reputation']?></li>
        <li>Nombre de ventes : <?=$userInfos['nb_vente']?></li>
        <li><label>
            Description : <?=htmlspecialchars($userInfos['description_perso'])?>
            <textarea class="edit_input" name="description_perso" maxlength="300" hidden><?=$userInfos['description_perso']?></textarea>
        </label></li>
    </ul>

    <script src="js/edit_profile.js"></script>

    <img src="images/edit.svg" alt="éditer le profil" width="30" onclick="edit_profile()">

    <input type="submit" class="edit_input" hidden>
</form>

<?php echo 'Bienvenu ' . $_SESSION['pseudo'] . ', <a href="logout.php">se déconnecter</a>.';