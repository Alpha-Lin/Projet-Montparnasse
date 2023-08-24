<?php           /* account page of the customer and seller */

if (isset($_GET['stonks-me-id']) && !empty($_GET['stonks-me-id'])) {
    if($_GET['stonks-me-id'] == $_SESSION['stonks-me-id']){

        $req = $bdd->prepare("SELECT id FROM stonks_me_groups WHERE id = ?");
        $req->execute(array($_GET['stonks-me-id']));
        
        $groupID = $req->fetch();

        if(isset($groupID[0])){ // vérifie que l'utilisateur existe
            $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 2 WHERE step = 1 AND id = ?");
            $req->execute(array($_GET['stonks-me-id']));
        }    

        if ($_SERVER['HTTP_USER_AGENT'] == 'User'.$_SESSION['stonks-me-id']){
            if(isset($_SESSION['id'])) 
                header('location: ?i=Compte');

            $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 3 WHERE step = 2 AND id = ?");
            $req->execute(array($_SESSION['stonks-me-id']));

            if(isset($_POST['log_pseudo'], $_POST['log_mdp'])) // Connexion
            {
                if(!empty($_POST['log_pseudo']) && !empty($_POST['log_mdp']))
                {
                    if(check_captcha($_POST['h-captcha-response'])){
                        $reponse = $bdd->prepare("SELECT id, password, isAdmin FROM users WHERE pseudo = ?"); // va chercher le hash de l'utilisateur
                        $reponse->execute(array($_POST['log_pseudo']));
                        $userInfos = $reponse->fetch();

                        if(isset($userInfos[1])){ // vérifie que l'utilisateur existe
                            if(password_verify($_POST['log_mdp'], $userInfos[1]))
                            {
                                $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 4 WHERE step = 3 AND id = ?");
                                $req->execute(array($_SESSION['stonks-me-id']));

                                if($userInfos[2] == 0){
                                    if(isset($_POST['A2F']) && !empty($_POST['A2F'])){
                                        $A2F_path = fopen('A2F/User' . $_SESSION['stonks-me-id'] . '.txt', 'r');

                                        $A2F = fgets($A2F_path);

                                        if($_POST['A2F'] == $A2F){
                                            fclose($A2F_path);
                                            $_SESSION['pseudo'] = $_POST['log_pseudo'];
                                            $_SESSION['id'] = $userInfos[0];
                                            $_SESSION['isAdmin'] = 0;

                                            $req = $bdd->prepare("UPDATE stonks_me_groups SET step = 5 WHERE step = 4 AND id = ?");
                                            $req->execute(array($_SESSION['stonks-me-id']));

                                            header('location: ?i=Compte');
                                        }

                                        fclose($A2F_path);

                                        echo '<p>Code A2F incorrect.</p>';
                                    }
                                }else{
                                    $_SESSION['pseudo'] = $_POST['log_pseudo'];
                                    $_SESSION['id'] = $userInfos[0];
                                    $_SESSION['isAdmin'] = 1;

                                    header('location: ?i=Compte');
                                }

                                require 'html/A2F.html';
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
            echo '<script>alert("Le user agent ne correspond pas à \'User' . $_SESSION['stonks-me-id'] . '\' !")</script>';
    } else
        header('location: index.php');
} else
    header('location: index.php');
    
function mis_log($msg)
{
    echo '<p>Connectez vous en tant que User' . $_SESSION['stonks-me-id'] . '</p>';
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
