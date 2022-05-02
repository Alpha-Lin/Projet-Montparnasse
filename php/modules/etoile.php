<link rel="stylesheet" href="css/etoile.css">

<?php
// TODO : faire des étoiles plus précises
function reputationStars($reputation){
	$stars = '<div class="stars">';

	for ($i = 1; $i < 6; $i++) {
		if($reputation >= $i)
			$stars .= '<i class="fa fa-star gold"></i>';
		else
			$stars .= '<i class="fa fa-star"></i>';
	}

	return $stars . '</div>';
}
?>
