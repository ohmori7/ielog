<?php
function
comment_get($realestate)
{

	$sql = "SELECT c.*, u.picture
	    FROM comment AS c, user AS u
	    WHERE
	        c.realestate = $realestate AND
	        c.user = u.id";
	return db_records_get_sql($sql);
}
?>
