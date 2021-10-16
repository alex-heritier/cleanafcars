<?

require_once 'lib/check_expiration.php';
require_once 'lib/scrape_cl_search.php';
require_once 'lib/save_car.php';

# Check expiration
$check_exp_result = check_expiration();

# Scrape CL listings
$scrape_url = 'https://sfbay.craigslist.org/search/cto?auto_title_status=1&max_price=8000&min_auto_year=1984&min_price=500&query=Mercedes';
$results = scrape_craigslist_search($scrape_url);
foreach ($results as $res) {
  save_car($res);
}
