<?php

namespace ServerZone\SupermicroIpmi\Tests\v03_48_X9DRH_7F;

use ServerZone\SupermicroIpmi\Client as SMCLient;
use ServerZone\SupermicroIpmi\Tests;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

use Tester\Assert;

require __DIR__ . '/../Skeleton.php';

class Test extends Tests\Skeleton
{

    /**
     * Login test
     *
     */

    protected function setupValidLogin(SMClient $instance)
    {
        $headers = [
            'Set-Cookie: SID=; expires=Thursday,01-Jan-1970 00:00:00 GMT; HttpOnly',
            'Set-Cookie: SID=upofwlfcuqjttkxh; path=/ ; HttpOnly'
        ];

        $responses = [
            new Response(200), // No redirect here
            new Response(200, $headers, file_get_contents(__DIR__ . '/login.valid.html')),
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }

    protected function setupInvalidLogin(SMClient $instance)
    {
        $responses = [
            new Response(200), // No redirect here
            new Response(200, [], file_get_contents(__DIR__ . '/login.invalid.html'))
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }

    /**
     * Power status test
     *
     */

    protected function setupPowerStatus(SMClient $instance)
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/powerStatus.on.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/powerStatus.off.html')),
        ];
        $instance->setHttpClient($this->createMockClient($responses));
    }

    /**
     * Setup power on test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    protected function setupPowerOn(SMClient $instance): void
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/powerStatus.on.html')),
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }

    /**
     * Setup power off test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    protected function setupPowerOff(SMClient $instance): void
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/powerStatus.off.html')),
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }

    /**
     * Setup power restart test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    protected function setupPowerRestart(SMClient $instance): void
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/powerStatus.on.html')),
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }

    /**
     * Power consumption test
     *
     */

    protected function setupPowerConsumption(SMClient $instance): int
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/powerConsumption.empty.html')),
            new Response(200, [], file_get_contents(__DIR__ . '/powerConsumption.valid.html'))
        ];

        $instance->setHttpClient($this->createMockClient($responses));

        return 62;
    }

    protected function validatePowerConsumption()
    {
        Assert::same(2, count($this->history));
    }

    /**
     * Setup user list test.
     *
     * @param SMClient $instance SMClient instance
     * @return array
     */
    protected function setupUserList(SMClient $instance): array
    {
        $responses = [
            new Response(200, [], file_get_contents(__DIR__ . '/userList.valid.html'))
        ];

        $instance->setHttpClient($this->createMockClient($responses));

        return [
            [ 'id' => 0, 'name' => '                ', 'access' => '00' ],
            [ 'id' => 1, 'name' => 'ADMIN', 'access' => '04' ],
            [ 'id' => 2, 'name' => 'zabbix', 'access' => '03' ],
        ];
    }

    /**
     * Setup user create test
     *
     * @param SMCLient $instance SMCLient instance
     */
    protected function setupUserCreate(SMClient $instance): void
    {
        $responses = [
            new Response(200, [], 'ok')
        ];

        $instance->setHttpClient($this->createMockClient($responses));
    }
}

(new Test)->run();
