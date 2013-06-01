<?
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();


try {
	// open connection to MongoDB server
	$conn = new Mongo('localhost');

	// access database
	$db = $conn->bicing;

	// access collection
	$data_collection = $db->data;
	$station_collection = $db->station;

/*
 *  Station resource
 */
include('./api/station.php');

$app->run();

// disconnect from server
$conn->close();
} catch (MongoConnectionException $e) {
	die('Error connecting to MongoDB server');
} catch (MongoException $e) {
	die('Error: ' . $e->getMessage());
}

?>
