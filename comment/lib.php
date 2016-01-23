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

function
comment_delete_all($realestate)
{

	return db_record_delete('comment', array('realestate' => $realestate));
}
?>
