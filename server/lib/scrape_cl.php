<?

function scrape_craigslist($url) {
  $raw_html = file_get_contents($url);

  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($raw_html);
  $xpath = new DomXPath($doc);


  # Price
  $nodeList = $xpath->query("//span[@class='price']");
  $node = $nodeList->item(0);
  $price = preg_replace("/[^0-9]/", "", trim($node->nodeValue));

  # Model
  $nodeList = $xpath->query("//p[@class='attrgroup']");
  $node = $nodeList->item(0);
  $full_model = trim($node->nodeValue);
  $model = NULL;
  preg_match("/^\d\d\d\d ?(.+)$/", $full_model, $model);
  $model = $model[1];

  # Year
  $year = NULL;
  preg_match("/\d\d\d\d/", $full_model, $year);
  $year = $year[0];

  # Transmission
  $transmission = NULL;
  preg_match("/<span>transmission: <b>(\w+)<\/b><\/span>/", $raw_html, $transmission);
  $transmission = $transmission[1];

  # Miles
  ## From specs
  $miles = NULL;
  preg_match("/<span>odometer: <b>(\d+)<\/b><\/span>/", $raw_html, $miles);
  $miles = $miles[1];
  ## From description using format xxx,xxx miles
  if (empty($miles)) {
    $miles = NULL;
    preg_match("/(\d?\d\d,?\d\d\d) miles?/", $raw_html, $miles);
    $miles = $miles[1];
  }
  ## From description using format xxxk/K miles
  if (empty($miles)) {
    $miles = NULL;
    preg_match("/(\d?\d?\d?,?\d?\d\d)[kK] miles?/", $raw_html, $miles);
    $miles = $miles[1];
    if (!empty($miles)) {
      $miles = $miles . "000";
    }
  }

  # Title Status
  $title_status = NULL;
  preg_match("/<span>title status: <b>(\w+)<\/b><\/span>/", $raw_html, $title_status);
  $title_status = $title_status[1];

  # Drive
  $drive = NULL;
  preg_match("/<span>drive: <b>(\w+)<\/b><\/span>/", $raw_html, $drive);
  $drive = $drive[1];

  # Images
  $images = [];
  $nodeList = $xpath->query("//div[@id='thumbs']//a/@href");
  foreach ($nodeList as $n) {
    // echo "\$n<br>";
    // var_dump($n);
    // echo "<br><br>\$n->nodeValue<br>";
    // print_r($n->nodeValue);
    // echo "<br><br>";
    array_push($images, $n->nodeValue);
  }

  # City
  $nodeList = $xpath->query("//span[@class='postingtitletext']//small");
  $raw_city = trim($nodeList->item(0)->nodeValue);
  $city = NULL;
  preg_match("/^\(?(.+)\)$/", $raw_city, $city);
  $city = $city[1];

  # Location
  $location = [
    "lat" => $xpath->query("//div[@id='map']//@data-latitude")->item(0)->nodeValue,
    "lng" => $xpath->query("//div[@id='map']//@data-longitude")->item(0)->nodeValue,
  ];

  # Posting body
  $nodeList = $xpath->query("//section[@id='postingbody']");
  $posting_body = $nodeList->item(0)->nodeValue;
  $posting_body = trim(str_replace("QR Code Link to This Post", "", $posting_body)); # This is hidden in the HTML


  $response = array_filter([
    "price" => $price,
    "model" => $model,
    "year" => $year,
    "transmission" => $transmission,
    "miles" => $miles,
    "title_status" => $title_status,
    "drive" => $drive,
    "city" => $city,
    "location" => $location,
    "body" => $posting_body,
    "images" => $images,
    "cl_link" => $url,
  ]);

  return $response;
}
