<?

require_once 'lib/scrape_cl.php';

$get_param_url = $_GET["url"];
$response = scrape_craigslist($get_param_url);

echo json_encode($response);
