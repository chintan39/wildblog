<?php
// This code was created by phpMyBackupPro v.2.1 
// http://www.phpMyBackupPro.net
chdir("../../../..");
require_once('app/config/default.inc');
require_once(DIR_PORK . 'class.settings.php');

$dbConfig = Settings::Load()->Get('Database');

$_POST['db']=array($dbConfig['database']);
$_POST['tables']="on";
$_POST['data']="on";
$_POST['drop']="on";
$_POST['zip']="zip";
$_POST['mysql_host']="-1";
$CONF['sql_host'] = $dbConfig['host'];
$CONF['sql_user'] = $dbConfig['username'];
$CONF['sql_passwd'] = $dbConfig['password'];
$CONF['sql_db'] = $dbConfig['database'];

$period=(3600*24);
$security_key="50f7932016fb6d59caa9af7259b59330";
// This is the relative path to the phpMyBackupPro v.2.1 directory
chdir(DIR_LIBS . "phpMyBackupPro/phpMyBackupPro/");
require("backup.php");
echo "OK";
?>