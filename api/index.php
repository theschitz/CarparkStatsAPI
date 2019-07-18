<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once 'config/database.php';
include_once 'objects/parking.php';

#TODO: Better http response codes
if (!isset($_GET)) {
    http_response_code(503);
    echo json_encode(
        array("message" => "No results found.")
    );
    die();
}
if (count($_GET) == 0) {
    http_response_code(503);
    echo json_encode(
        array("message" => "No results found.")
    );
}

#TODO: Sanitize user input
#TODO: Collect params

$database = new Database();
$db = $database->getConnection();
$parking = new Parking($db);
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
            "name" => $name,
            "description" => html_entity_decode($description),
        );
 
        array_push($parking_arr["records"], $parking_area);
    }

    http_response_code(200);
    echo json_encode($parking_arr);
    die();
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No results found.")
    );
}

?>