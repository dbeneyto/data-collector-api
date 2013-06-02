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
 * /api/system/{system-name}/station/near/lat/{xxx.xxx}/lon/{yyy.yyy}/{num-results}
* i.e: http:///bdc.labobila.com/api/system/bicing/station/near/lat/41.370297/lon/2.187808/3
* Get {num-result} stations near to provided coordinates from {system-name}
*/
$app->get('/api/system/:bikesystem/station/near/lat/:lat/lon/:lon/:results','near')->name('bikesystemname','lat','lon','results');
function near($bikesystemname,$lat,$lon,$results) {
	try {
		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		//		$collection->ensureIndex(array("location" => "2d"));

		$location = array((float)$lon,(float)$lat);

		$station = $db->command(array(
				'geoNear' => "station",      	// Search in the station collection
				'near' => $location, 			// Search near $location
				'spherical' => false,           // Disable spherical search
				'num' => (int)$results,         // Maximum $results returned documents
		));
		echo json_encode($station);

		// disconnect from server
		$conn->close();
	} catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	} catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
}

/*
 * /api/system/{system-name}/station/{station-id}/within/{x-meters}/{num-results}
* i.e: http:///bdc.labobila.com/api/system/bicing/station/400/within/500/3
* Get {num-result} stations near to provided {station-id}
*/
$app->get('/api/system/:bikesystem/station/:id/within/:meters/:results','stationswithin')->name('bikesystemname','idstation','meters','results');
function stationswithin($bikesystemname,$idstation,$meters,$results) {
	try {

		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$collectionQuery=array('id'=>$idstation);
		$cursor = $collection->find($collectionQuery)->limit(1);

		foreach ($cursor as $obj) {
			$location = $obj['location'];
		}

		//                $collection->ensureIndex(array("location" => "2d"));

		$nearbystations = $db->command(array(
				'geoNear' => "station",      					// Search in the station collection
				'near' => $location,            				// Search near $location
				'spherical' => false,        					// Disable spherical search
				'maxDistance' => float(int($meters/111120)),	// 1 degree ~ 111.12 km
				'num' => (int)$results,         				// Maximum returned documents
		));
		echo json_encode($nearbystations);

		// disconnect from server
		$conn->close();
	} catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	} catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
}

/*
 * /api/system/{system-name}/station/{station-id}/near/{num-results}
* i.e: http:///bdc.labobila.com/api/system/bicing/station/400/near/3
* Get {num-result} stations near to provided {station-id}
*/
$app->get('/api/system/:bikesystem/station/:id/near/:results','nearbystations')->name('bikesystemname','idstation','results');
function nearbystations($bikesystemname,$idstation,$results) {
	try {

		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$collectionQuery=array('id'=>$idstation);
		$cursor = $collection->find($collectionQuery)->limit(1);

		foreach ($cursor as $obj) {
			$location = $obj['location'];
		}

		//                $collection->ensureIndex(array("location" => "2d"));

		$nearbystations = $db->command(array(
				'geoNear' => "station",      // Search in the station collection
				'near' => $location,             // Search near $location
				'spherical' => false,        // Disable spherical search
				'num' => (int)$results,           // Maximum returned documents
		));
		echo json_encode($nearbystations);

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
