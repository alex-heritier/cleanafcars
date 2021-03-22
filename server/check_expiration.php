<?

$expiration_indicators = [
  "This posting has been deleted by its author.",
  "The post has expired, or the post ID in the URL is invalid.",
];

$db_path = $argv[1] ?? "../db/cars.json";

$json = json_decode(file_get_contents($db_path), true);

$expired_count = 0;
foreach ($json["cars"] as $id => $car) {
  // Download the page's HTML
  $raw_html = NULL;
  try {
    $raw_html = file_get_contents($car['cl_link']);
  } catch (Exception $e) {
    $raw_html = NULL;
  }

  // Check the HTML for possible page expiration
  if ($raw_html == NULL) {
    $is_valid = false;
  } else {
    $is_valid = true;
    foreach ($expiration_indicators as $exp_indicator) {
      $matches = [];
      preg_match_all("/" . preg_quote($exp_indicator) .  "/", $raw_html, $matches, PREG_OFFSET_CAPTURE);
      $is_valid = $is_valid && (count($matches) == 1 && count($matches[0]) == 0);
    }
  }

  if (!$is_valid) {
    unset($json["cars"][$id]);
    $expired_count++;
  }
}

# Write database
$w_file = fopen($db_path, "w");
flock($w_file, LOCK_EX);
fwrite($w_file, json_encode($json));

echo json_encode($expired_count);
