<?

$json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/db/cars.json"), true);

echo <<<HTML

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Clean AF Cars</title>

  <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700|Roboto:400|Bebas+Neue:400" rel="stylesheet">
  <link rel="icon" href="/icon.jpg" />
  <link href="/css/page/index.css" rel="stylesheet">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-176138363-1">
  </script>
  <script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-176138363-1');
  </script>
</head>
<body>
  <!-- Header -->
  <div class="header">
    <span>Clean AF Cars</span>
  </div>

  <!-- Content -->
  <div class="content">

HTML;

$cars = $json["cars"];

# Sort cars to show newest ones first
usort($cars, function($a, $b) {
    return $b['created_at'] - $a['created_at'];
});

foreach ($cars as $i => $car) {
  $id = $car['id'];
  $thumbnail = $car['images'][0];
  $model = ucwords($car['model']);
  $price = $car['price'];
  $transmission = ucfirst($car['transmission']);
  $miles = $car['miles'];
  $year = $car['year'];

  $title = "$year $model";
  $formatted_miles = empty($miles) ? "" : "$miles miles |";

  echo <<<HTML

  <div class="content-item">
    <a href="/car.php?id=$id">
      <img src="$thumbnail"/>
    </a>
    <div class="label">
      <h2>$title</h2>
      <p>\$$price | $transmission | $formatted_miles $year</p>
    </div>
  </div>

  HTML;
}


echo <<<HTML

</div>

<!-- Footer -->
<div class="footer">
  <div class="divider"></div>
  <div class="nav">
    <p>© Clean AF Cars</p>
    <p><a href="/contact.html">Contact</a></p>
    <p><a href="/privacy.html">Privacy</a></p>
  </div>
</div>
</body>
</html>

HTML;
