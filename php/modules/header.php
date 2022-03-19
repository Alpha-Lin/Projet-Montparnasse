<?php
require 'html/header.html';

if(isset($_SESSION['id']))
    echo '<li><a href="?i=upload_announcement" style="float: right"><i class="fa fa-plus" aria-hidden="true"></i></a></li>';
?>
</ul>
<div class="volet" onclick="location.href='/js/nePasSupprimer.html';">
    <div class="arrow"></div>
</div>
<div class="sidebar">
    <div class="circle" onclick="location.href='/';">
            <p>Click</p>
    </div>
    <div class="circle" onclick="location.href='/';">
            <p>Click</p>
    </div>
    <div class="circle" onclick="location.href='/';">
            <p>Click</p>
    </div>
</div>

<div class="main-content">

