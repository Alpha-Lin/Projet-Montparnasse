<?php require '../php/modules/init_bdd.php';?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création groupe</title>
</head>
<body>
<?php
    if(isset($_GET['pass']) && !empty($_GET['pass'])){
        if($_GET['pass'] == 'stonksForEver2023'){
            if(isset($_POST['nom']) && !empty($_POST['nom']))
            {
                $req = $bdd->prepare('INSERT INTO stonks_me_groups(name) VALUES (?)');
                $success = $req->execute(array($_POST['nom']));

                if($success){
                    $user = 'User' . $bdd->lastInsertId();
                    
                    $req = $bdd->prepare('INSERT INTO users(pseudo, lastName, firstName, country, email, phone, password) VALUES (?, ?, ?, "France", ?, ?, ?)');
                    $success = $req->execute(array($user,
                                        $user,
                                        $user,
                                        $user . '@gmail.com',
                                        rand(1000000000, 9999999999),
                                        password_hash($user, PASSWORD_ARGON2ID))
                    );

                    $A2F = fopen('../A2F/' . $user . '.txt', 'w');
                    fwrite($A2F, rand(100000, 999999));
                    fclose($myfile);
                }

                if($success)
                    echo '<p>Compte ajoué</p>';
                else
                    echo '<p>Erreur lors de l\'ajout</p>';
            }

            require '../html/admin_stonks-me.html';
        }
    }
?>
</body>
</html>
