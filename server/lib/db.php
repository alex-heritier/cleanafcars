<?php

const DB_CAR = 'cars.json';
const DB_EMAIL = 'emails.json';

function db_name_to_path($db_name) {
  return $_SERVER['DOCUMENT_ROOT'] . "/db/" . $db_name;
}

function read_db($db_name) {
  $path = db_name_to_path($db_name);
  return json_decode(file_get_contents($path), true);
}

function save_db($db_name, $data) {
  $path = db_name_to_path($db_name);
  $w_file = fopen($path, "w");
  flock($w_file, LOCK_EX);
  fwrite($w_file, json_encode($data));
}
