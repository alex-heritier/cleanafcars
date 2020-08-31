<?

$redirect_url = $_POST["redirect_url"];

$car_data = $_POST;
$car_id_param = $car_data["id"];
unset($car_data["id"]);
unset($car_data["redirect_url"]);

# Format input data
$car_data["model"] = ucwords($car_data["model"]);
$car_data["transmission"] = ucwords($car_data["transmission"]);
$car_data["title_status"] = ucwords($car_data["title_status"]);
$car_data["city"] = ucwords($car_data["city"]);
$car_data["drive"] = strtoupper($car_data["drive"]);
$car_data["price"] = number_format($car_data["price"]);
if (!empty($car_data["miles"])) {
  $car_data["miles"] = number_format($car_data["miles"]);
}
if (empty($car_data["thumbnail"])) {
  $thumb = ($car_data["images"] ?? [NULL])[0];
  if ($thumb != NULL) $car_data["thumbnail"] = $thumb;
}

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

  # Alert newsletter users
  $email_db = $_SERVER['DOCUMENT_ROOT'] . "/db/emails.json";
  $email_json = json_decode(file_get_contents($email_db), true);
  foreach ($email_json["emails"] as $email) {
    $subject = "Clean AF Cars - " . $car_data["year"] . " " . $car_data["model"];
    $message = "https://cleanafcars.com/car.php?id=" . $new_id;
    $headers = 'From: Clean AF Cars' . "\r\n" .
      'Reply-To: alex.heritier@gmail.com' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
    mail($email, $subject, $message, $headers);
  }
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
