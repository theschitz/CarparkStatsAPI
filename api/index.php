<?php
//debug -->
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//debug <--
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';
include_once 'objects/parking.php';

testRequest();
$valid_query_params = array("limit", "fromDatetime", "toDatetime", "name", "orderby");
$filters = getFilterArray($valid_query_params);

$database = new Database();
$db = $database->getConnection();
$parking = new Parking($db);
$parking->setFilters($filters);
$stmt = $parking->read();
$num = $stmt->rowCount();
 
if($num > 0){
    $parking_arr = array();
    $parking_arr["records"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

function getFilterArray($valid_params) {
    $f = array();
    foreach ($_GET as $key => $value) {
        if (!in_array($key, $valid_params)) {
            http_response_code(400);
            echo json_encode(
                array("message" => "Parameter [{$key}] not supported.")
            );
            die();
        } else {
            $f[$key] = htmlspecialchars(strip_tags($value));
        }
    }
    return $f;
}

function testRequest() {
    if ($_SERVER['REQUEST_METHOD'] != "GET") {
        http_response_code(405);
        echo json_encode(
            array("message" => "Only GET requests accepted.")
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
}
?>