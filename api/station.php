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
		$collection->ensureIndex(array("location" => "2d"));

		$location = array((float)$lon,(float)$lat);

		$station = $db->command(array(
				'geoNear' => "station",      // Search in the poiConcat collection
				'near' => $location, // Search near 51.48°N, 0.08°E
				'spherical' => false,           // Enable spherical search
				'num' => $results,                    // Maximum 5 returned documents
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
 * /api/system/{system-name}/station/{station-id}/near/{num-results}
* i.e: http:///bdc.labobila.com/api/system/bicing/station/400/near/3
* Get {num-result} stations near to provided {station-id}
*/
$app->get('/api/system/:bikesystem/station/:id/near/:results','nearbystations')->name('bikesystemname','id','results');
function nearbystations($bikesystemname,$id,$results) {
	try {

		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$collectionQuery=array('id'=>$idstation);
		$cursor = $collection->find($collectionQuery)->limit(1);
		
		foreach ($cursor as $obj) {
			$station=$obj;
		}
		
		$collection->ensureIndex(array("location" => "2d"));

		$location = array($station['lon'],$station['lat']);

		$station = $db->command(array(
				'geoNear' => "station",      // Search in the station collection
				'near' => $location, 		 // Search near $location
				'spherical' => false,        // Disable spherical search
				'num' => $results,           // Maximum returned documents
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

/*
 * /api/system/{system-name}/station/near?lat=xxx.xxx&lon=yyy.yyy
* i.e: http:///bdc.labobila.com/api/system/bicing/station/near?lat=41.370297&lon=2.187808
* Get @station neares to provided coordinates from {system-name}
*/
$app->get('/api/system/:bikesystem/station/near','near')->name('bikesystemname');
function near($bikesystemname) {
	try {
		$conn = new Mongo(DB_SERVER_IP);
		$db = $conn->$bikesystemname;
		$collection = $db->station;
		$collection->ensureIndex(array("location" => "2d"));

		$lon = $app->request()->get('lon');
		$lat = $app->request()->get('lat');

		$location = array($lon, $lat);

		echo json_encode($location);

		$station = $db->command(array(
				'geoNear' => "station",      // Search in the poiConcat collection
				'near' => $location, // Search near 51.48°N, 0.08°E
				'spherical' => true,           // Enable spherical search
				'num' => 1,                    // Maximum 5 returned documents
		));
		print_r($r);

		// disconnect from server
		$conn->close();
	} catch (MongoConnectionException $e) {
		die('Error connecting to MongoDB server');
	} catch (MongoException $e) {
		die('Error: ' . $e->getMessage());
	}
}

?>
