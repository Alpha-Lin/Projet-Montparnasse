<?php
require 'html/header.html';

if(isset($_SESSION['id']))
    echo '<a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a>';
?>
</nav> 

<?php

require 'html/slide_down_menu.html';  // à déplacer plus tard

require 'html/sidebar.html';

?>

<main>
