<?php     /* the front page */
require 'php/init_bdd.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'php/header.php';

if(isset($_GET['i']) && !empty($_GET['i']))
{
    $path_include = 'php/' . $_GET['i'] . '.php';

    if(dirname($path_include) === 'php')
        require $path_include;
}

require 'html/footer.html';

?>

