<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Utils;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same(31, Utils::SensorFunc("1f", "0100", "0000", "00"));
Assert::same(90, Utils::SensorFunc("5a", "0100", "0000", "00"));
Assert::same(85, Utils::SensorFunc("55", "0100", "0000", "00"));

Assert::same(-5, Utils::SensorFunc("-5", "0100", "0000", "00"));
Assert::same(-7, Utils::SensorFunc("-7", "0100", "0000", "00"));
