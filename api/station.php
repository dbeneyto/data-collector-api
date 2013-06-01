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
    echo "Get all stations from $bikesystemname bike system. TO-DO";
}



?>
