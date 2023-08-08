<?php           /* account page of the customer and seller */

if (isset($_GET['stonks-me-id'])) {

    $req = $bdd->prepare("SELECT id FROM stonks_me_groups WHERE id = ?");
    $req->execute(array($_GET['stonks-me-id']));
    
    $groupID = $req->fetch();

    if(isset($groupID[0])){ // vérifie que l'utilisateur existe
        $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 2 WHERE id = ?");
        $req->execute(array($_GET['stonks-me-id']));
    }    
}

if ($_SERVER['HTTP_USER_AGENT'] == 'user'.$_SESSION['stonks-me-id']){
    if(isset($_SESSION['id'])) {
        $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 3 WHERE id = ?");
        $req->execute(array($_SESSION['stonks-me-id']));
        header('location: ?i=Compte');
    }

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
    }else
        mis_log("");
}else
    echo '<script>alert("Le user agent ne correspond pas à \'userX\' !")</script>';
    
function mis_log($msg)
{
    echo '<p>Connectez vous en tant que user' . $_SESSION['stonks-me-id'] . '</p>';
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
