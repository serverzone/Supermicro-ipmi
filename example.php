<?php

require __DIR__ . '/vendor/autoload.php';

$instance = new ServerZone\SupermicroIpmi\Client('my-ipmi.local', 'ADMIN', 'ADMIN');
$instance = new ServerZone\SupermicroIpmi\Client('192.168.0.1', 'ADMIN', 'ADMIN');
$instance = new ServerZone\SupermicroIpmi\Client('[2001:db8::1]', 'ADMIN', 'ADMIN');

// Power status

echo 'Power status:';
var_dump($instance->getPowerStatus());

echo "\n\n";

// Power consumption
echo 'Power consumption:';
var_dump($i->getPowerConsumption());

echo "\n\n";

// Power on/off and reset
// $instance->powerOn();
// $instance->powerOff();
// $instance->powerReset();

// Sensors
echo 'Sensors';
var_dump($instance->getSensors());
