<?php

namespace Notic185\PHP_SDK;

require '../vendor/autoload.php';

$mangrove = new Mangrove(
    "http://10.0.0.254:4003",
    [
        "externalId" => "sbSO9eCvjQkE38hVnrVy4TiD",
        "secret" => "ER3wT3UP05TVEyh8CdMnZsCz5I1j0z",
    ],
);

print_r($mangrove->order->handleCallback());
