<?php

require_once 'lib/db.php';

$to = "alex.heritier@gmail.com";
$subject = "Clean AF Cars - Newsletter signup";
$message = $_POST['email'] .  " registered for the newsletter.";
$headers = 'From: Clean AF Cars' . "\r\n" .
  'Reply-To: no-reply@cleanafcars.com' . "\r\n" .
  'X-Mailer: PHP/' . phpversion();

// Append email to email list
$json = read_db(DB_EMAIL);

$new_id = $json["curr_email_id"]++;
$json["emails"][$new_id] = $_POST['email'];

# Write database
save_db(DB_EMAIL, $json);

// Send myself an email
mail($to, $subject, $message);

echo true;
