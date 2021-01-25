<?php

namespace ServerZone\SupermicroIpmi;

interface IClient
{

    public function __construct(string $host, string $username, string $password);


    /**
     * Logins to the web interface
     *
     * @thorws UnauthorizedException on login failure
     */
    public function login(): void;

    /**
     * Return power status.
     *
     * @return boolean
     */
    public function getPowerStatus(): bool;

    /**
     * Power on action.
     *
     * @return void
     */
    public function powerOn(): void;

    /**
     * Power off action.
     *
     * @return void
     */
    public function powerOff(): void;

    /**
     * Power restart action.
     *
     * @return void
     */
    public function powerRestart(): void;

    /*
    public function getSensors();
    */

    /**
     * Returns overall status of the system
     *
     * Values:
     *  - 0: OK (or not available)
     *  - 1: Warning
     *  - 2: Critical
     *  - 3: Unknown
     *
     * @return int
     */
    public function getOverallStatus(): int;

    /**
     * Returns power consumption
     *
     */
    public function getPowerConsumption(): int;
}
