<?php

require __DIR__ . '/vendor/autoload.php';

$azimuth = new \Fbnio\Azimuth();

$me = new \Fbnio\Location(52.509915, 13.506030, 41);
$plane = new \Fbnio\Location(52.4772, 13.4841, 37000 * 0.3048);

$res = $azimuth->calculate($plane, $me);

var_dump($res);