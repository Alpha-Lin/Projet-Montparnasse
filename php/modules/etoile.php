<link rel="stylesheet" href="css/etoile.css">

<?php
function reputationStars($reputation){
	echo '<div class="stars">';

	for ($i = 1; $i < 6; $i++) {
		if($reputation >= $i)
			echo '<i class="fa fa-star gold"></i>';
		else
			echo '<i class="fa fa-star"></i>';
	}

	echo '</div>';
}
?>
