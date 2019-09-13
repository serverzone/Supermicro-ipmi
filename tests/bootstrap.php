<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

/**
 * Parses attribudes from xml node
 *
 * This is used to prepare sensors data
 *
 * @param $line Sensor line (e.g. <SENSOR ID="001" NUMBER="01" NAME="CPU1 Temp" ... />
 */
function parseXmlNodeAttributes(string $line)
{
    $result = [];

    preg_match_all('#\s([^=]+)="([^"]+)#', $line, $output, PREG_SET_ORDER);
    foreach ($output as $row) {
        $result[$row[1]] = $row[2];
    }

    return $result;
}
