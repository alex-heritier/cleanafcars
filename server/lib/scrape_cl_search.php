<?

require_once 'scrape_cl.php';

function scrape_craigslist_search($url, $limit = 20) {
  $raw_html = file_get_contents($url);

  libxml_use_internal_errors(true);
  $doc = new DOMDocument();
  $doc->loadHTML($raw_html);
  $xpath = new DomXPath($doc);


  # Results
  $results = [];
  $nodeList = $xpath->query("//li[@class='result-row']/a[@class='result-image gallery']/@href");
  $count = 0;
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

    $count++;
    if ($count >= $limit) {
      break;
    }
  }

  return $results;
}
