<?php

// koneksi ke database

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'sms';

mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);

?>