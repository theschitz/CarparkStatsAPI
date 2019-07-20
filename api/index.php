<?php
//debug -->
ini_set('display_errors', 1);
error_reporting(E_ALL);
//debug <--
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';
include_once 'objects/parking.php';

if (!isset($_GET)) {
    http_response_code(400);
    echo json_encode(
        array("message" => "Only GET requests allowed.")
    );
    die();
}
if (count($_GET) == 0) {
    http_response_code(400);
    echo json_encode(
        array("message" => "No parameters supplied.")
    );
    die();
}
$valid_parms = array("limit", "fromDatetime", "toDatetime", "name", "orderby");
$filters = array();
foreach ($_GET as $key => $value) {
    if (!in_array($key, $valid_parms)) {
        http_response_code(400);
        echo json_encode(
            array("message" => "Parameter [{$key}] not supported.")
        );
        die();
    } else {
        $filters[$key] = htmlspecialchars(strip_tags($value));
    }
}

$database = new Database();
$db = $database->getConnection();
$parking = new Parking($db);
$parking->setFilters($filters);
$stmt = $parking->read();
$num = $stmt->rowCount();
 
if($num > 0){
    $parking_arr = array();
    $parking_arr["records"] = array();
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row this will make $row['name'] to just $name only
        extract($row);
        $parking_area = array(
            "id" => $id,
            "datetime" => $datetime,
            "name" => $name,
            "occupancy" => $occupancy,
            "maxoccupancy" => $maxoccupancy,
            "marginal" => $marginal,
            "hysteres" => $hysteres,
            "active" => $active
        );
 
        array_push($parking_arr["records"], $parking_area);
    }

    http_response_code(200);
    echo json_encode($parking_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No results found.")
    );
}

?>