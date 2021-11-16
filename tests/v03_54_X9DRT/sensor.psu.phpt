<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Sensor;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

// OK Power supply

$sensor = '<SENSOR ID="01d" NUMBER="c8" NAME="PS1 Status" READING="010100" OPTION="c0" UNR="01" UC="01" UNC="00" LNC="01" LC="00" LNR="02" STYPE="08" RTYPE="02" ERTYPE="6f" UNIT1="c0" UNIT="00" L="00" M="0000" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('PS1 Status', $sensor->getName());
Assert::same('', $sensor->getUnits());
Assert::same(0, $sensor->getStatus());

// Failed Power supply

$sensor = '<SENSOR ID="032" NUMBER="c8" NAME="PS1 Status" READING="020300" OPTION="c0" UNR="00" UC="00" UNC="00" LNC="00" LC="00" LNR="00" STYPE="08" RTYPE="02" ERTYPE="6f" UNIT1="c0" UNIT="00" L="00" M="0000" B="0000" RB="00"/>';
$sensor = new Sensor(parseXmlNodeAttributes($sensor));
Assert::same('PS1 Status', $sensor->getName());
Assert::same('', $sensor->getUnits());
Assert::same(2, $sensor->getStatus());
