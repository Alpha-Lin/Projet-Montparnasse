<?php
require 'php/modules/init_bdd.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'php/modules/header.php';

if(isset($_GET['i']) && !empty($_GET['i']))
{
    $path_include = 'php/' . $_GET['i'] . '.php';

    if(dirname($path_include) === 'php')
        require $path_include;
}else if(isset($_GET['search']))
    require 'php/search.php';
else if(isset($_GET['paiement']))
    require 'php/paiement.php';
else
    require 'php/carousel.php';

require 'html/footer.html';

?>
