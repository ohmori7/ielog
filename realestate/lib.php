<?php
define('REALESTATE_PAYMENT_TYPE_RENTAL',		1);
define('REALESTATE_PAYMENT_TYPE_SALE',			2);
define('REALESTATE_PAYMENT_TYPE_RENTAL_AND_SALE',	3);

function
realestate_data_dirbase($id)
{

	return 'realestate/' . $id . '/';
}


function
realestate_data_dir($id)
{

	return IELOG_DATADIR . '/' . realestate_data_dirbase($id);
}

function
realestate_data_url($id, $filename)
{

	return '../file.php?path=' . realestate_data_dirbase($id) . $filename;
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
realestate_image_top($r)
{

	if (array_key_exists('id', $r) && array_key_exists('picture', $r)) {
		$id = $r['id'];
		$pic = $r['picture'];
		if (file_exists(realestate_data_dir($id) . $pic))
			return realestate_data_url($id, $pic);
	}
	return IELOG_URI . 'images/appear1.png'; /* XXX: should replace */
}
?>
