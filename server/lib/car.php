<?php

require_once 'db.php';

function get_car($car_id) {
  $data = read_db(DB_CAR);
  return $data["cars"][$car_id];
}

function list_cars() {
  $data = read_db(DB_CAR);
  $cars = [];
  foreach ($data["cars"] as $id => $car) {
    array_push($cars, $car);
  }
  return $cars;
}

function delete_car($car_id) {
  $data = read_db(DB_CAR);
  unset($data['cars'][$car_id]);
  save_db(DB_CAR, $data);
}
