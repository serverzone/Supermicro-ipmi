<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// 12V voltage

$sensor = '<SENSOR ID="01b" NUMBER="37" NAME="12V" READING="72c000" OPTION="c0" UNR="83" UC="80" UNC="7d" LNC="66" LC="63" LNR="60" STYPE="02" RTYPE="01" ERTYPE="01" UNIT1="00" UNIT="04" L="00" M="6a00" B="0000" RB="d0"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('12V', $sensor->getName());
Assert::same('Volts', $sensor->getUnits());
Assert::same(12.084, $sensor->getValue());
Assert::same(10.176, $sensor->getLimit('LNR'));
Assert::same(10.494, $sensor->getLimit('LC'));
Assert::same(10.812, $sensor->getLimit('LNC'));
Assert::same(13.25, $sensor->getLimit('UNC'));
Assert::same(13.568, $sensor->getLimit('UC'));
Assert::same(13.886, round($sensor->getLimit('UNR'), 3));
Assert::same(0, $sensor->getStatus());
