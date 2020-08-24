<?

$car_id = $_GET['id'];

$json = json_decode(file_get_contents("../db/cars.json"), true);

$response = NULL;

if (empty($car_id)) {
  $response = [];
  foreach ($json["cars"] as $id => $car) {
    array_push($response, $car);
  }
} else {
  $response = $json["cars"][$car_id];
}

echo json_encode($response);
