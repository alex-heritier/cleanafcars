<?

require_once 'lib/car.php';

$car_id = $_GET['id'];

$response = NULL;
if (empty($car_id)) {
  $response = list_cars();
} else {
  $response = get_car($car_id);
}

echo json_encode($response);
