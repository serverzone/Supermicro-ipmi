<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// OK FAN

$sensor = '<SENSOR ID="00e" NUMBER="41" NAME="FAN1" READING="4bc000" OPTION="c0" UNR="ff" UC="fe" UNC="fd" LNC="08" LC="06" LNR="04" STYPE="04" RTYPE="01" ERTYPE="01" UNIT1="00" UNIT="12" L="00" M="4b00" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('FAN1', $sensor->getName());
Assert::same('RPM', $sensor->getUnits());
Assert::same(5625, $sensor->getValue());
Assert::same(300, $sensor->getLimit('LNR'));
Assert::same(450, $sensor->getLimit('LC'));
Assert::same(600, $sensor->getLimit('LNC'));
Assert::same(18975, $sensor->getLimit('UNC'));
Assert::same(19050, $sensor->getLimit('UC'));
Assert::same(19125, $sensor->getLimit('UNR'));
Assert::same(0, $sensor->getStatus());
