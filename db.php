<?php
/* XXX: these are very old API but a MySQL of VPS server is very old. */
$dbconnection = FALSE;

function
db_init($c)
{
	global $dbname;

	if (! mysql_query("CREATE DATABASE $dbname"))
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
	        'picture CHAR(255)',
	        'prefecture CHAR(255)',
	        'city CHAR(255)',
	        'address CHAR(255)',
	        'birthday DATETIME',
	        'PRIMARY KEY(id)'
	        ),
	    'realestate' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'owner INT NOT NULL',
		'abstract TEXT NOT NULL',
		'description MEDIUMTEXT NOT NULL',
		'payment TINYINT UNSIGNED NOT NULL',
		'builtdate DATETIME NOT NULL',
		'picture CHAR(255)',
	        'prefecture CHAR(255)',
	        'city CHAR(255)',
	        'address CHAR(255)',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (owner) REFERENCES user(id)'
	        ),
	    'comment' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'realestate INT',
		'assessment INT',
		'message MEDIUMTEXT',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (realestate) REFERENCES realestate(id)'
	        ),
	    );

	foreach ($tables as $name => $cols) {
		$sql = "CREATE TABLE $name (" . implode(', ', $cols) . ')';
		if (! mysql_query($sql))
			die("Cannot create table: $name: " . mysql_error());
	}

	return TRUE;
}

function
db_choose()
{
	global $dbname;

	return mysql_query("USE $dbname");
}

function
db_connect()
{
	global $dbserver, $dbuser, $dbpasswd, $dbconnection;

	if ($dbconnection !== FALSE)
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
db_record_insert($table, $obj)
{
	global $dbconnection;

	db_connect();
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
db_record_get($table, $key, $value)
{

	db_connect();
	$value = db_addslashes($value);
	$sql = "SELECT * FROM $table WHERE $key = '$value'";
	return db_fetch(db_sql($sql));
}

function
db_records_get($table)
{

	db_connect();
	$sql = "SELECT * FROM $table";
	$rs = db_sql($sql);
	if ($rs === false)
		return $rs;

	$records = array();
	while ($r = db_fetch($rs))
		$records[$r['id']] = $r;

	return $records;
}

function
db_close()
{

	if ($dbconnction === FALSE)
		return;
	mysql_close($dbconnection);
	$dbconnection = FALSE;
}
?>
