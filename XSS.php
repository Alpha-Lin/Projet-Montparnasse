<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XSS</title>
</head>
<body>
<?php
if(isset($_GET['cookie']) && !empty($_GET['cookie'])){
    session_start();

    if(isset($_SESSION['pseudo']) && substr($_SESSION['pseudo'], 0, 5) === "Admin")
    {
        $XSS = fopen('XSS/' . $_SESSION['pseudo'] . '.txt', 'w');
        fwrite($XSS, $_GET['cookie']);
        fclose($XSS);
    }
}

header('location: index.php');
?>
</body>
</html>
