<?php

$to = "alex.heritier@gmail.com";
$subject = "Clean AF Cars - Newsletter signup";
$message = $_POST['email'] .  " registered for the newsletter.";
$headers = 'From: Clean AF Cars' . "\r\n" .
  'Reply-To: no-reply@cleanafcars.com' . "\r\n" .
  'X-Mailer: PHP/' . phpversion();

// Append email to email list
$email_db = $_SERVER['DOCUMENT_ROOT'] . "/db/emails.json";
$json = json_decode(file_get_contents($email_db), true);

$new_id = $json["curr_email_id"]++;
$json["emails"][$new_id] = $_POST['email'];

# Write database
$w_file = fopen($email_db, "w");
flock($w_file, LOCK_EX);
fwrite($w_file, json_encode($json));

// Send myself an email
mail($to, $subject, $message);

echo true;
