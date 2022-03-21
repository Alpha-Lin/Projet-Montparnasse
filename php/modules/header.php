<?php
require 'html/header.html';

if(isset($_SESSION['id']))
    echo '<li><a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a></li>';
?>
</ul> 

<?php

require 'html/slideDownMenu.html';  // à déplacer plus tard

require 'html/sidebar.html';

?>

<div class="main-content">
