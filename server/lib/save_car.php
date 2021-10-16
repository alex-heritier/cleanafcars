<?

require_once 'scrape_cl.php';
require_once 'db.php';

function save_car($car_data) {
  $car_id_param = $car_data["id"];
  unset($car_data["id"]);

  # Format input data
  $car_data["model"] = ucwords($car_data["model"]);
  $car_data["transmission"] = ucwords($car_data["transmission"]);
  $car_data["title_status"] = ucwords($car_data["title_status"]);
  $car_data["city"] = ucwords($car_data["city"]);
  $car_data["drive"] = strtoupper($car_data["drive"]);
  $car_data["price"] = number_format(preg_replace("/[$,]/i", "", $car_data["price"]));
  if (!empty($car_data["miles"])) {
    $car_data["miles"] = number_format($car_data["miles"]);
  }
  if (empty($car_data["thumbnail"])) {
    $thumb = ($car_data["images"] ?? [NULL])[0];
    if ($thumb != NULL) $car_data["thumbnail"] = $thumb;
  }

  # Read database
  $json = read_db(DB_CAR);

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
    $email_json = read_db(DB_EMAIL);
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
  save_db(DB_CAR, $json);
}
