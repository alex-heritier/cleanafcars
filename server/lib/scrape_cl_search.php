<?

require_once 'scrape_cl.php';

function scrape_craigslist_search($url) {
  // $url = "https://sfbay.craigslist.org/sby/cto/d/san-jose-2009-lexus-ls-460-sedan-4d/7181359035.html";
  // $url = "https://sfbay.craigslist.org/sby/cto/d/san-jose-1971-camaro-trade-for-2010-and/7181377982.html";
  // $url = "https://sfbay.craigslist.org/eby/cto/d/vacaville-1982-pontiac-grand-prix-top/7181371488.html";
  // $url = "https://sfbay.craigslist.org/eby/ctd/d/san-leandro-2017-toyota-prius-one/7182502238.html";
  $test_url = "https://sfbay.craigslist.org/d/cars-trucks-by-owner/search/cto?auto_bodytype=11&auto_bodytype=2&auto_bodytype=3&auto_bodytype=4&auto_bodytype=8&auto_drivetrain=2&auto_title_status=1&auto_transmission=1&max_price=8000&min_auto_year=1985&min_price=500";
  if (empty($url)) {
    $url = $test_url;
  }

  $raw_html = file_get_contents($url);

  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($raw_html);
  $xpath = new DomXPath($doc);


  # Results
  $results = [];
  $nodeList = $xpath->query("//li[@class='result-row']/a[@class='result-image gallery']/@href");
  foreach ($nodeList as $n) {
    $url = $n->nodeValue;

    // Sleep to stop craigslist from blocking us
    $sleep_time = rand(1, 3);
    sleep($sleep_time);

    // echo "#### ROW START<br>";
    // print_r($url);
    // echo "<br>";
    // echo "#### ROW END<br>";
    // echo "<br><br>";

    // Scrape posting
    $response = scrape_craigslist($url);
    array_push($results, $response);
  }

  return $results;
}
