<?php
/* XXX: these are very old API but a MySQL of VPS server is very old. */
$dbconnection = false;

function
db_init($c)
{
	global $dbname;

	if (! db_sql("CREATE DATABASE $dbname"))
		die('Cannot create database');

	if (! db_choose())
		die('Cannot choose database');

	$tables = array(
	    'user' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
	        'mail CHAR(255) UNIQUE NOT NULL',
		'password CHAR(255) NOT NULL',
	        'lastname CHAR(255) NOT NULL',
	        'firstname CHAR(255) NOT NULL',
	        'birthday DATE NOT NULL',
	        'picture CHAR(255)',
		'zip CHAR(16)',
	        'prefecture CHAR(255)',
	        'city CHAR(255)',
	        'address CHAR(255)',
		'lastmodified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ' .
		    'ON UPDATE CURRENT_TIMESTAMP',
		'created TIMESTAMP',
	        'PRIMARY KEY(id)'
	        ),
	    'realestate' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'owner INT NOT NULL',
		'abstract TEXT NOT NULL',
		'description MEDIUMTEXT NOT NULL',
		'contract TINYINT UNSIGNED NOT NULL',
		'builtdate DATE NOT NULL',
		'zip CHAR(16)',
		'picture CHAR(255)',
	        'prefecture CHAR(255)',
	        'city CHAR(255)',
	        'address CHAR(255)',
		'lastmodified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ' .
		    'ON UPDATE CURRENT_TIMESTAMP',
		'created TIMESTAMP',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (owner) REFERENCES user(id)'
	        ),
	    'realestate_like' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'user INT NOT NULL',
		'realestate INT NOT NULL',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (user) REFERENCES user(id)',
	        'FOREIGN KEY (realestate) REFERENCES realestate(id)'
	        ),
	    'comment' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'realestate INT NOT NULL',
		'user INT NOT NULL',
		'comment MEDIUMTEXT NOT NULL',
		'lastmodified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ' .
		    'ON UPDATE CURRENT_TIMESTAMP',
		'created TIMESTAMP',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (realestate) REFERENCES realestate(id)',
	        'FOREIGN KEY (user) REFERENCES user(id)'
	        ),
	    );

	foreach ($tables as $name => $cols) {
		$sql = "CREATE TABLE $name (" . implode(', ', $cols) . ')';
		if (! db_sql($sql))
			die("Cannot create table: $name: " . mysql_error());
	}

	return true;
}

function
db_choose()
{
	global $dbname;

	return db_sql("USE $dbname");
}

function
db_connect()
{
	global $dbserver, $dbuser, $dbpasswd, $dbconnection;

	if ($dbconnection !== false)
		return;

	$dbconnection = mysql_connect($dbserver, $dbuser, $dbpasswd);
	if (! $dbconnection)
		die('Databse connection error');

	if (! db_choose() && ! db_init($dbconnection))
		die('Cannot open database');
}

function
db_sql($sql)
{

	db_connect();
	return mysql_query($sql);
}

function
db_fetch($rs)
{

	return mysql_fetch_assoc($rs);
}

function
db_error()
{

	return mysql_error();
}

function
db_addslashes($s)
{

	return mysql_real_escape_string($s);
}

function
db_record_key_values($obj)
{

	if (empty($obj))
		return '';
	$keyvalues = array();
	foreach ((Array)$obj as $key => $value)
		$keyvalues[] = $key . ' = "' . db_addslashes($value) . '"';
	return $keyvalues;
}

function
db_record_insert($table, $obj)
{
	global $dbconnection;

	$keys = array_keys((Array)$obj);
	$values = array_map('db_addslashes', array_values((Array)$obj));
	$sql = "INSERT INTO $table (" . implode(',', $keys) . ") VALUES (\"" .
	    implode('","', $values) . '")';
	$rc = db_sql($sql);
	if ($rc === true)
		$rc = @mysql_insert_id($dbconnection);
	return $rc;
}

function
db_record_update($table, $obj)
{
	global $dbconnection;

	$a = (Array)$obj;
	$id = $a['id'];
	unset($a['id']);
	$keyvalues = db_record_key_values($a);
	$sql = "UPDATE $table
	    SET " .  implode(', ', $keyvalues) . '
	    WHERE id = ' . $id;
	if (db_sql($sql) !== true)
		return false;
	return $id;
}

function
db_record_where($cond)
{

	$keyvalues = db_record_key_values($cond);
	if (empty($keyvalues))
		return '';
	return ' WHERE ' . implode(' AND ', $keyvalues);
}

function
db_record_delete($table, $cond)
{

	if (empty($cond))
		return false;
	$sql = "DELETE FROM $table" . db_record_where($cond);
	return db_sql($sql);
}

function
db_record_get($table, $cond = array())
{

	$sql = "SELECT * FROM $table " . db_record_where($cond);
	return db_fetch(db_sql($sql));
}

function
db_records_get_sql($sql)
{
	$rs = db_sql($sql);
	if ($rs === false)
		return $rs;

	$records = array();
	while ($r = db_fetch($rs))
		$records[$r['id']] = $r;

	return $records;
}

function
db_records_get($table)
{
	$sql = "SELECT * FROM $table";
	return db_records_get_sql($sql);
}

function
db_records_count($table, $cond = array())
{

	$sql = "SELECT COUNT(*) AS count FROM $table " . db_record_where($cond);
	$count = db_fetch(db_sql($sql));
	if (! is_array($count))
		return $count;
	return (int)$count['count'];
}

function
db_close()
{

	if ($dbconnction === false)
		return;
	mysql_close($dbconnection);
	$dbconnection = false;
}
?>
