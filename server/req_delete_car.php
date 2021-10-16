<?

require_once 'lib/car.php';

$car_id = $_POST['id'];

delete_car($car_id);

echo json_encode(true);
