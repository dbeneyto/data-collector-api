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
	$station_query = $station_collection->find();
	
	$stations=array();
	foreach ($station_query as $obj) {
		$lat=$obj['lat']/1000000;
		$lng=$obj['lng']/1000000;
		$ocupation=$obj['free']*100/($obj['bikes']+$obj['free']);
		$marker=array($lat,$lng,$ocupation);
		array_push($stations,json_encode($marker));
	}
	
	echo json_encode($stations);
}



?>
