<?php
define('REALESTATE_PAYMENT_TYPE_RENTAL',		1);
define('REALESTATE_PAYMENT_TYPE_SALE',			2);
define('REALESTATE_PAYMENT_TYPE_RENTAL_AND_SALE',	3);

function
realestate_data_dir($id)
{

	return IELOG_DATADIR . '/realestate/' . $id . '/';
}

function
realestate_add($values)
{
	global $USER;

	unset($values['MAX_FILE_SIZE']);
	$values['owner'] = $USER->id;
	return db_record_insert('realestate', $values);
}

function
realestate_image_top($id)
{

	return IELOG_URI . 'images/appear1.png'; /* XXX */
}
?>
