<?php

require '../vendor/autoload.php';

date_default_timezone_set('UTC');

use Carbon\Carbon;

$faker = Faker\Factory::create();

$vehicleNumbers = range(1,10);

$devices = array_map(function ($vehicleNumber) use ($faker) {
    $lastReportedAtRandom = $faker->dateTimeBetween('1 month ago');
    $lastReportedAt =  $faker->optional(0.3, $lastReportedAtRandom)->dateTimeBetween('24 hours ago');

    return [
        'id' => $faker->uuid,
        'label' => 'Vehicle ' . $vehicleNumber,
        'lastReportedAt' => $lastReportedAt->format('Y-m-d H:i:s'),
        'status' => Carbon::instance($lastReportedAt)->lessThan(Carbon::now('UTC')->subHours(24)) ? 'OFFLINE' : 'OK',
    ];
}, $vehicleNumbers);

header('Content-Type: application/json');

echo json_encode(['items' => $devices]);
