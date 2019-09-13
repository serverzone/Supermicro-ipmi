<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// OK Dimm Temp

$sensor = '<SENSOR ID="006" NUMBER="b0" NAME="P1-DIMMA1 TEMP" READING="1ec000" OPTION="c0" UNR="5a" UC="55" UNC="50" LNC="04" LC="02" LNR="01" STYPE="01" RTYPE="01" ERTYPE="01" UNIT1="00" UNIT="01" L="00" M="0100" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('P1-DIMMA1 TEMP', $sensor->getName());
Assert::same('degrees C', $sensor->getUnits());
Assert::same(30, $sensor->getValue());
Assert::same(1, $sensor->getLimit('LNR'));
Assert::same(2, $sensor->getLimit('LC'));
Assert::same(4, $sensor->getLimit('LNC'));
Assert::same(80, $sensor->getLimit('UNC'));
Assert::same(85, $sensor->getLimit('UC'));
Assert::same(90, $sensor->getLimit('UNR'));
Assert::same(0, $sensor->getStatus());
