<?php




	if (is_resource($issuesResult))
		mysqli_free_result($issuesResult);
	if (is_resource($publicationsResult))
		mysqli_free_result($publicationsResult);

/*
	if (is_resource($frequenciesResult))
		mysqli_free_result($frequenciesResult);

	if (is_resource($updateResult))
		mysqli_free_result($updateResult);
	if (is_resource($confirmResult))
		mysqli_free_result($confirmResult);
	if (is_resource($describeResult))
		mysqli_free_result($describeResult);
	*/
	mysqli_close( $mysqli );
?>