<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// OK System temp

$sensor = '<SENSOR ID="003" NUMBER="11" NAME="System Temp" READING="1ec000" OPTION="c0" UNR="5a" UC="55" UNC="50" LNC="fb" LC="f9" LNR="f7" STYPE="01" RTYPE="01" ERTYPE="01" UNIT1="80" UNIT="01" L="00" M="0100" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('System Temp', $sensor->getName());
Assert::same('degrees C', $sensor->getUnits());
Assert::same(30, $sensor->getValue());
Assert::same(-9, $sensor->getLimit('LNR'));
Assert::same(-7, $sensor->getLimit('LC'));
Assert::same(-5, $sensor->getLimit('LNC'));
Assert::same(80, $sensor->getLimit('UNC'));
Assert::same(85, $sensor->getLimit('UC'));
Assert::same(90, $sensor->getLimit('UNR'));
Assert::same(0, $sensor->getStatus());
