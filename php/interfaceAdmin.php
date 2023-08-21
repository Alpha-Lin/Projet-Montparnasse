<link rel="stylesheet" href="../css/eventStyle.css">

<form class="commandPrompt" method="POST">
    <pre>> Quel site voulez vous ping ?</pre>
    <pre>> <input class="prompt" type="text" name="site" autofocus> </pre>
    
        <?php
            if (isset($_POST['site']) && !empty($_POST['site'])) {
                $site = $_POST['site'];
                $command = "bash -c 'ping -c 3 $site 2>&1'";
                $response = shell_exec($command);
                
                echo "<pre>$response</pre>";
            }
        
            
        ?>
    <input type="submit">
</form>



<?php

