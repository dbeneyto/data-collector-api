<?

/*
 * /api/system
* i.e: http:///bdc.labobila.com/api/system
* Get @all bike systems recorded
*/
$app->get('/api/system/','bikesystem');
function bikesystem() {
	echo "Get all bike systems recorderd. TO-DO";
}

/*
 * /api/system/{system-name}/station
* i.e: http:///bdc.labobila.com/api/system/bicing/station
* Get @all stations from {system-name}
*/
$app->get('/api/system/:bikesystem/station','stations')->name('bikesystemname');
function stations($bikesystemname) {
	try {
		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$cursor = $collection->find();

		$stations=array();
		foreach ($cursor as $obj) {
			$station_info=array($obj['id'],$obj['cleaname'],$obj['location']);
			array_push($stations,json_encode($station_info));
		}
		echo json_encode($stations);

		// disconnect from server
		$conn->close();
	} catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	} catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
}

/*
 * /api/system/{system-name}/station/{station-id}
* i.e: http:///bdc.labobila.com/api/system/bicing/station/1
* Get @all data from {system-name} and station {station-id}
*/
$app->get('/api/system/:bikesystem/station/:id','station')->name('bikesystemname','idstation');
function station($bikesystemname,$idstation) {
	try {
		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$collectionQuery=array('id'=>$idstation);
		$cursor = $collection->find($collectionQuery);

		foreach ($cursor as $obj) {
			echo json_encode($obj);
		}
		// disconnect from server
		$conn->close();
	} catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	} catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
}


?>
