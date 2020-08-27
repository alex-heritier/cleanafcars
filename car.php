<?

$id = $_GET['id'];
$json = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/db/cars.json"), true);

echo <<<HTML

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Clean AF Cars | Car</title>

  <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700|Roboto:400|Bebas+Neue:400" rel="stylesheet">
  <link rel="icon" href="/icon.jpg" />
  <link href="/css/page/car.css" rel="stylesheet">

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
    <a href="/">Clean AF Cars</a>
  </div>

  <!-- Content -->
  <div class="content">
HTML;

$car = $json["cars"][$id];
$thumbnail = $car['images'][0];
$year = $car['year'];
$model = $car['model'];
$price = $car['price'];
$description = $car['description'];
$transmission = $car['transmission'];
$miles = $car['miles'];
$cl_link = $car['cl_link'];

$title = "$year $model";
$formatted_miles = empty($miles) ? "" : "$miles miles";

echo <<<HTML

<div class="gallery">
  <img class="banner" src="$thumbnail"/>
</div>

<div class="info">
  <h2>$title</h2>
  <p class="description">$description</p>

  <div class="spec">
    <p class="price">\$$price</p>
    <p>$transmission</p>
    <p>$formatted_miles</p>
  </div>

  <a href="$cl_link">View on Craigslist</a>
</div>

HTML;

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
