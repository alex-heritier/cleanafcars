<?

$redirect_url = $_POST["redirect_url"];

$car_data = $_POST;
$car_id_param = $car_data["id"];
unset($car_data["id"]);
unset($car_data["redirect_url"]);

# Read database
$json = json_decode(file_get_contents("../db/cars.json"), true);

# Modify database
if (empty($car_id_param)) { # no car_id, therefore CREATE car
  $new_id = $json["curr_car_id"] + 1;
  $json["curr_car_id"] = $new_id;

  $car_data["id"] = $new_id;

  $timestamp = time();
  $car_data["updated_at"] = $timestamp;
  $car_data["created_at"] = $timestamp;

  $json["cars"][$new_id] = $car_data;
} else { # car_id exists, therefore EDIT car
  if (isset($json["cars"][$car_id_param])) {
    $timestamp = time();
    $car_data["updated_at"] = $timestamp;

    foreach ($car_data as $key => $val) {
      $json["cars"][$car_id_param][$key] = $val;
    }
  }
}

# Write database
$w_file = fopen("../db/cars.json", "w");
flock($w_file, LOCK_EX);
fwrite($w_file, json_encode($json));

if (!empty($redirect_url)) {
  header('Location: ' . $redirect_url);
} else {
  echo file_get_contents("../db/cars.json");
}
