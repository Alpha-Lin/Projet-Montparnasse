<?php           /* account page of the customer and seller */
if(isset($_SESSION['id']))
    header('location: ?i=Compte');

if(isset($_POST['log_pseudo'], $_POST['log_mdp'])) // Connexion
{
    if(!empty($_POST['log_pseudo']) && !empty($_POST['log_mdp']))
    {
        if(check_captcha($_POST['h-captcha-response'])){
            $reponse = $bdd->prepare("SELECT id, password FROM users WHERE pseudo = ?"); // va chercher le hash de l'utilisateur
            $reponse->execute(array($_POST['log_pseudo']));
            $userInfos = $reponse->fetch();

            if(isset($userInfos[1])){ // vérifie que l'utilisateur existe
                if(password_verify($_POST['log_mdp'], $userInfos[1]))
                {
                    $_SESSION['pseudo'] = $_POST['log_pseudo'];
                    $_SESSION['id'] = $userInfos[0];

                    header('location: ?i=Compte');
                }else
                    mis_log("Erreur : mot de passe incorrect.");
            }else
                mis_log("Erreur : compte inconnu.");
        }
    }else
        mis_log("Paramètre(s) vide(s).");
}else if(isset($_POST['prenom'], $_POST['nom'], $_POST['pseudo'], $_POST['email'], $_POST['mdp'])) // Inscription
{
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['mdp']))
    {
        if(check_captcha($_POST['h-captcha-response']))
        {
            $req = $bdd->prepare('INSERT INTO users(pseudo, lastName, firstName, country, email, phone, password, newsletter) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $req->execute(array($_POST['pseudo'],
                                $_POST['nom'],
                                $_POST['prenom'],
                                $_POST['pays'],
                                $_POST['email'],
                                $_POST['tel'],
                                password_hash($_POST['mdp'], PASSWORD_ARGON2ID),
                                isset($_POST['newsletter']) ? 1 : 0)
            );
            mis_log('Inscription réussi, veuillez vous connecter.');
        }
    }
}else
    require 'html/register_login.html';

function mis_log($msg)
{
    echo '<p>' . $msg . '</p>';
    require 'html/register_login.html';
}

function check_captcha($captcha_response)
{
    require 'php/modules/hcaptcha.php';

    if(hcaptcha($captcha_response))
        return true;
    
    mis_log("Captcha invalide !");
    return false;
}
?>
