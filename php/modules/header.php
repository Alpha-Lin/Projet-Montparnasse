<?php
require 'html/header.html';

if(isset($_SESSION['id']))
    echo '<a href="?i=panier" id="panier" style="float: right"><i class="fa fa-shopping-basket" id="iconeB" aria-hidden="true"></i></a>
          <a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a>';
?>

</nav> 

<?php
    if(isset($_GET['search']) && !empty($_GET['search']))
        require 'html/slide_down_menu.html';
?>

<main>
