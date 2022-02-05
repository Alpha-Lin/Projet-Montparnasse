<?php           /* account page of the customer and seller */
if(isset($_SESSION['pseudo'])) // Session
{
    echo 'Bienvenu ' . $_SESSION['pseudo'] . ', <a href="logout.php">se déconnecter</a>.';
}else if(isset($_POST['log_pseudo'], $_POST['log_mdp'])) // Connexion
{
    if(!empty($_POST['log_pseudo']) && !empty($_POST['log_mdp']))
    {
        $reponse = $bdd->prepare("SELECT mot_de_passe FROM utilisateur WHERE pseudo = ?"); // va chercher le hash de l'utilisateur
		$reponse->execute(array($_POST['log_pseudo']));
		$userInfos = $reponse->fetch();

		if(isset($userInfos[0])){ // vérifie que l'utilisateur existe
			if(password_verify($_POST['log_mdp'], $userInfos[0]))
			{
				$_SESSION['pseudo'] = $_POST['log_pseudo'];

				header('location: index.php?i=Compte');
			}else
                mis_log("Erreur : mot de passe incorrect.");
		}else
            mis_log("Erreur : compte inconnu.");
    }
}else if(isset($_POST['prenom'], $_POST['nom'], $_POST['pseudo'], $_POST['email'], $_POST['mdp'])) // Inscription
{
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['mdp']))
    {
        $req = $bdd->prepare('INSERT INTO utilisateur(nom, prenom, pays, pseudo, email, mot_de_passe) VALUE (?, ?, ?, ?, ?, ?)');
        $req->execute(array($_POST['nom'],
                            $_POST['prenom'],
                            $_POST['pays'],
                            $_POST['pseudo'],
                            $_POST['email'],
                            password_hash($_POST['mdp'], PASSWORD_ARGON2ID))
        );
        mis_log('Inscription réussi, veuillez vous connecter.');
    }
}else
    require 'html/ongletForms.html';

function mis_log($msg)
{
    echo '<p>' . $msg . '</p>';
    require 'html/ongletForms.html';
}
?>
