<?
/**
 * Require the Slim Framework
 */
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();	

/*
 *  Station resource
 */
include('./api/station.php');

$app->run();

?>
