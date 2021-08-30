<?php
$GLOBALS['folder']='/istiBead/'; //absoluth path


if(!defined('host'))  define('host','localhost');
if(!defined('uname')) define('uname','duo');
if(!defined('pw'))    define('pw','2y3P5J6ukDscvkH');
if(!defined('db'))    define('db','duo');


/*
$conn = mysqli_connect(host,uname,pw,db) OR DIE('can not connect');

mysqli_set_charset($conn,"utf8");

try{
    $db = new pdo('mysql:host=localhost;dbname=me;charset=utf8');
}
catch(PDOException $e){
    die($e->getMessage());
}
*/


$database = new mysqli(host,uname,pw,db);
$database->set_charset("utf8");

/*foreach(glob('classes/*.class.php') as $file)
{
    require_once ($file);
}*/
include"classes/realEstate.class.php";
include"classes/messages.class.php";
include"classes/users.class.php";

RealEstate::set_database($database);
User::set_database($database);
Message::set_database($database);



