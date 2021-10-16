<?php

require_once 'lib/scrape_cl.php';
require_once 'lib/save_car.php';

$url = $_POST['url'];

$car_data = scrape_craigslist($url);
save_car($car_data);

echo json_encode('success');
