<?

$car_id = $_POST['id'];

# Read database
$json = json_decode(file_get_contents("../db/cars.json"), true);

unset($json['cars'][$car_id]);

# Write database
$w_file = fopen("../db/cars.json", "w");
flock($w_file, LOCK_EX);
fwrite($w_file, json_encode($json));

echo json_encode(true);
