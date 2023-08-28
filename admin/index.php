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
            if(isset($_GET['startSession']) && !empty($_GET['startSession'])){
                $req = $bdd->prepare('UPDATE stonks_me_sessions SET time_start = NOW(), stepLock = 1 WHERE time_start IS NULL AND id = ?');
                $success = $req->execute(array($_GET['startSession']));
            }
            else if(isset($_GET['stopSession']) && !empty($_GET['stopSession'])){
                $req = $bdd->prepare('UPDATE stonks_me_sessions SET time_end = NOW() WHERE time_start IS NOT NULL AND time_end IS NULL AND id = ?');
                $success = $req->execute(array($_GET['stopSession']));
            }
            else if(isset($_GET['session'], $_POST['stepLock']) && !empty($_GET['session']) && !empty($_POST['stepLock'])){
                $req = $bdd->prepare('UPDATE stonks_me_sessions SET stepLock = ? WHERE time_end IS NULL AND id = ?');
                $success = $req->execute(array($_POST['stepLock'],
                                    $_GET['session']));
            }
            else if(isset($_POST['nomSession']) && !empty($_POST['nomSession'])){
                $req = $bdd->prepare('INSERT INTO stonks_me_sessions(name) VALUES (?)');
                $success = $req->execute(array($_POST['nomSession']));

                if($success)
                    echo '<p>Session ajoutée</p>';
                else
                    echo '<p>Erreur lors de l\'ajout de la session</p>';
            }
            else if(isset($_POST['nomGroupe'], $_POST['sessionID'], $_POST['admin_pass']) && !empty($_POST['nomGroupe']) && !empty($_POST['sessionID']) && !empty($_POST['admin_pass']))
            {
                $req = $bdd->prepare('INSERT INTO stonks_me_groups(name, session) VALUES (?, ?)');
                $success = $req->execute(array($_POST['nomGroupe'],
                                    $_POST['sessionID']));

                if($success){
                    $idGroup = $bdd->lastInsertId();
                    $user = 'User' . $idGroup;
                    
                    $req = $bdd->prepare('INSERT INTO users(pseudo, lastName, firstName, country, email, phone, password, idGroupe) VALUES (?, ?, ?, "France", ?, ?, ?, ?)');
                    $success = $req->execute(array($user,
                                        $user,
                                        $user,
                                        $user . '@gmail.com',
                                        rand(1000000000, 9999999999),
                                        password_hash($user, PASSWORD_ARGON2ID),
                                        $idGroup)
                    );

                    $A2F = fopen('../A2F/' . $user . '.txt', 'w');
                    fwrite($A2F, rand(100000, 999999));
                    fclose($A2F);

                    $flag = fopen('../flags/flag_' . $idGroup . '.txt', 'w');
                    fwrite($flag, md5(rand()));
                    fclose($flag);

                    if($success){
                        $admin = 'Admin' . $idGroup;

                        $req = $bdd->prepare('INSERT INTO users(pseudo, lastName, firstName, country, email, phone, password, isAdmin, idGroupe) VALUES (?, ?, ?, "France", ?, ?, ?, 1, ?)');
                        $success = $req->execute(array($admin,
                                            $admin,
                                            $admin,
                                            $admin . '@gmail.com',
                                            rand(1000000000, 9999999999),
                                            password_hash($_POST['admin_pass'], PASSWORD_ARGON2ID),
                                            $idGroup)
                        );
                    }
                }

                if($success)
                    echo '<p>Groupe ajouté</p>';
                else
                    echo '<p>Erreur lors de l\'ajout du groupe</p>';
            }

            $req = $bdd->query('SELECT * FROM stonks_me_sessions');
            $sessions = $req->fetchAll();

            require '../html/admin_stonks-me.html';
        }
    }
?>
</body>
</html>
