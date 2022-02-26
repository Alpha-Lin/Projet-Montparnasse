<?php
require 'html/header.html';

if(isset($_SESSION['id']))
    echo '<li><a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a></li>';
?>
</ul>
</div>