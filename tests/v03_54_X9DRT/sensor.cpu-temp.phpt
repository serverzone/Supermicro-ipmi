<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// OK CPU Temp

$sensor = '<SENSOR ID="001" NUMBER="01" NAME="CPU1 Temp" READING="20c000" OPTION="c0" UNR="48" UC="46" UNC="43" LNC="00" LC="00" LNR="00" STYPE="01" RTYPE="01" ERTYPE="01" UNIT1="80" UNIT="01" L="00" M="0100" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('CPU1 Temp', $sensor->getName());
Assert::same('degrees C', $sensor->getUnits());
Assert::same(32, $sensor->getValue());
Assert::same(0, $sensor->getLimit('LNR'));
Assert::same(0, $sensor->getLimit('LC'));
Assert::same(0, $sensor->getLimit('LNC'));
Assert::same(67, $sensor->getLimit('UNC'));
Assert::same(70, $sensor->getLimit('UC'));
Assert::same(72, $sensor->getLimit('UNR'));
Assert::same(0, $sensor->getStatus());

$sensor = '<SENSOR ID="002" NUMBER="02" NAME="CPU2 Temp" READING="1ec000" OPTION="c0" UNR="48" UC="46" UNC="43" LNC="00" LC="00" LNR="00" STYPE="01" RTYPE="01" ERTYPE="01" UNIT1="80" UNIT="01" L="00" M="0100" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('CPU2 Temp', $sensor->getName());
Assert::same(30, $sensor->getValue());
Assert::same(0, $sensor->getLimit('LNR'));
Assert::same(0, $sensor->getLimit('LC'));
Assert::same(0, $sensor->getLimit('LNC'));
Assert::same(67, $sensor->getLimit('UNC'));
Assert::same(70, $sensor->getLimit('UC'));
Assert::same(72, $sensor->getLimit('UNR'));
Assert::same(0, $sensor->getstatus());

// Turned off

$sensor = '<SENSOR ID="001" NUMBER="01" NAME="CPU1 Temp" READING="000000" OPTION="00" UNR="64" UC="62" UNC="5f" LNC="00" LC="00" LNR="00" STYPE="01" RTYPE="01" ERTYPE="01" UNIT1="80" UNIT="01" L="00" M="0100" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same(0, $sensor->getValue());
Assert::same(0, $sensor->getStatus());
