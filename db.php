<?php
/*
 * this requires a configuration file, config.php, like below.
 * <?php
 * $dbserver	= 'localhost';
 * $dbuser	= 'username';
 * $dbpasswd	= 'password';
 * $dbname	= 'ielog';
 * ?>
 */
require_once('config.php');

/* XXX: these are very old API but a MySQL of VPS server is very old. */

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
	        'lastname CHAR(255)',
	        'firstname CHAR(255)',
	        'mail CHAR(255)',
	        'picture CHAR(255)',
	        'PRIMARY KEY(id)'
	        ),
	    'realestate' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'owner INT NOT NULL',
		'abstract VARCHAR(65535)',
		'description VARCHAR(65535)',
		'picture CHAR(255)',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (owner) REFERENCES user(id)'
	        ),
	    'comment' => array(
	        'id INT NOT NULL AUTO_INCREMENT',
		'realestate INT',
		'assessment INT',
		'message VARCHAR(65535)',
	        'PRIMARY KEY(id)',
	        'FOREIGN KEY (realestate) REFERENCES realestate(id)'
	        ),
	    );

	foreach ($tables as $name => $cols) {
		$sql = "CREATE TABLE $name (" . implode(', ', $cols) . ')';
		echo $sql;
		if (! mysql_query($sql))
			die('Cannot create table: ' . $name);
	}

	return true;
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
	global $dbserver, $dbuser, $dbpasswd;

	$c = mysql_connect($dbserver, $dbuser, $dbpasswd);
	if (! $c)
		die('Databse connection error');

	if (! db_choose() && ! db_init($c))
		die('Cannot open database');
}

function
db_sql($sql)
{
	
	return mysql_query($sql);
}

function
db_close($c)
{

	mysql_close($c);
}
?>
