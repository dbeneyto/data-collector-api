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
$app->get('/api/system/:bikesystem/station','station')->name('bikesystemname');
function station($bikesystemname) {
try {
  // open connection to MongoDB server
  $conn = new Mongo('localhost');

  // access database
  $db = $conn->bicing;

  // access collection
  $collection = $db->station;

  // execute query
  // retrieve all documents
  $cursor = $collection->find()->limit(400);

  // iterate through the result set
  // print each document
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


?>
