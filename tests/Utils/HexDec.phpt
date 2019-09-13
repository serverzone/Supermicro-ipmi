<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Utils;

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Assert::same(240, Utils::hexdec("f0"));
Assert::same(-5, Utils::hexdec("-5"));

Assert::same("f0", Utils::dechex(240));
Assert::same(-5, Utils::dechex("-5"));
