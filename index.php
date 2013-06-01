<?
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->get('/foo', function () {
    echo "Foo!";
});

include('./api/station.php');

$app->run();

?>
