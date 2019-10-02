<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type:text/html;charset='utf-8'");
define("BASEPATH",     getcwd());

include_once BASEPATH.'/configs/mysqlconfig.php';
include_once(BASEPATH."/controller/Controller.php");
include_once(BASEPATH."/model/User.php");

$mysqli = DB::connector();
$user = User::current();

if(empty($_GET['controller'])) $controllerName = "SiteController";
else$controllerName = ucfirst ($_GET['controller'])."Controller";
if(empty($_GET['action'])) $actionName = "actionIndex";
else $actionName = "action" . ucfirst ($_GET['action']);

$controllerPath = BASEPATH."/controller/". $controllerName .".php";

include_once($controllerPath);
$controller = new $controllerName();

$controller->user = $user;
$controller->{$actionName}();

?>
