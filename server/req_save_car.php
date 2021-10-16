<?

require_once 'lib/save_car.php';

$redirect_url = $_POST["redirect_url"];
$car_data = $_POST;

save_car($car_data);

if (!empty($redirect_url)) {
  header('Location: ' . $redirect_url);
} else {
  echo file_get_contents("../db/cars.json");
}
