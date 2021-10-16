<?

require_once 'db.php';

function check_expiration() {
  $expiration_indicators = [
    "This posting has been deleted by its author.",
    "The post has expired, or the post ID in the URL is invalid.",
    "This posting has expired.",
  ];

  $json = read_db(DB_CAR);

  $expired_count = 0;
  foreach ($json["cars"] as $id => $car) {
    // Sleep to stop craigslist from blocking us
    $sleep_time = rand(1, 3);
    sleep($sleep_time);

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
  save_db(DB_CAR, $json);

  return $expired_count;
}
