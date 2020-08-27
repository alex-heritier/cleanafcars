<?

$expiration_indicators = [
  "This posting has been deleted by its author.",
  "The post has expired, or the post ID in the URL is invalid.",
];

$json = json_decode(file_get_contents("../db/cars.json"), true);

$expired_count = 0;
foreach ($json["cars"] as $id => $car) {
  $raw_html = file_get_contents($car['cl_link']);

  $is_valid = true;
  foreach ($expiration_indicators as $exp_indicator) {
    $matches = [];
    preg_match_all("/" . preg_quote($exp_indicator) .  "/", $raw_html, $matches, PREG_OFFSET_CAPTURE);
    $is_valid = $is_valid && (count($matches) == 1 && count($matches[0]) == 0);
  }

  if (!$is_valid) {
    unset($json["cars"][$id]);
    $expired_count++;
  }
}

# Write database
$w_file = fopen("../db/cars.json", "w");
flock($w_file, LOCK_EX);
fwrite($w_file, json_encode($json));

echo json_encode($expired_count);
