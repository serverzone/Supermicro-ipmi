<?php

namespace ServerZone\SupermicroIpmi\Tests;

use ServerZone\SupermicroIpmi\Client as SMClient;

use Tester;
use Tester\Assert;

use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

require __DIR__ . '/bootstrap.php';

abstract class Skeleton extends Tester\TestCase
{

    private $instance;

    protected $history = [];

    /**
     * Valid login test
     *
     */

    abstract protected function setupValidLogin(SMClient $instance);

    public function testValidLogin()
    {
        $instance = new SMClient('127.0.0.1', 'ADMIN', 'ADMIN');

        $this->setupValidLogin($instance);

        $instance->login();

        $this->instance = $instance;
    }

    /**
     * Invalid login test
     *
     */

    abstract protected function setupInvalidLogin(SMClient $instance);

    public function testInvalidLogin()
    {
        $instance = new SMClient('127.0.0.1', 'ADMIN', 'WrongPassword');
        $this->setupInvalidLogin($instance);

        Assert::exception(function () use ($instance) {
            $instance->login();
        }, \ServerZone\SupermicroIpmi\UnauthorizedException::class);
    }

    /**
     * Power status test
     *
     */

    abstract protected function setupPowerStatus(SMClient $instance);

    public function testPowerStatus()
    {
        $this->setupPowerStatus($this->instance);

        // get power on
        Assert::same(true, $this->instance->getPowerStatus());

        // get power off
        Assert::same(false, $this->instance->getPowerStatus());
    }

    /**
     * Setup power on test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    abstract protected function setupPowerOn(SMClient $instance): void;

    /**
     * Power on test.
     */
    public function testPowerOn(): void
    {
        $this->setupPowerOn($this->instance);
        $this->instance->powerOn();
    }

    /**
     * Setup power off test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    abstract protected function setupPowerOff(SMClient $instance): void;

    /**
     * Power off test.
     */
    public function testPowerOff(): void
    {
        $this->setupPowerOff($this->instance);
        $this->instance->powerOff();
    }

    /**
     * Setup power restart test.
     *
     * @param SMClient $instance SMClient instance
     * @return void
     */
    abstract protected function setupPowerRestart(SMClient $instance): void;

    /**
     * Power restart test.
     */
    public function testPowerRestart(): void
    {
        $this->setupPowerRestart($this->instance);
        $this->instance->powerRestart();
    }

    /**
     * Power consumption test
     *
     */

    abstract protected function setupPowerConsumption(SMClient $instance): int;

    public function testPowerConsumption()
    {
        $value = $this->setupPowerConsumption($this->instance);

        Assert::same($value, $this->instance->getPowerConsumption());

        $this->validatePowerConsumption();
    }

    abstract protected function validatePowerConsumption();

    /** Support methods */

    protected function createMockClient($responses = [])
    {
        $this->history = [];
        $history = Middleware::history($this->history);

        $mock = new MockHandler($responses);

        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Client(['handler' => $handler]);

        return $client;
    }
}
