<link rel="stylesheet" href="css/etoile.css">

<div class="stars">
	<?php
	for ($i = 1; $i < 6; $i++) {
		if($userInfos['reputation'] >= $i)
			echo '<i class="fa fa-star gold"></i>';
		else
			echo '<i class="fa fa-star"></i>';
	}
	?>
</div>
