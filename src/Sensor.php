<?php

namespace ServerZone\SupermicroIpmi;

/**
 * Supermicro sensor
 *
 */
class Sensor
{

    /** @var array Raw data from constructor */
    private $source;

    /**
     *
     */
    public function __construct(array $sensor)
    {
        $this->source = $sensor;
    }

    /**
     * Returns sensor name
     *
     */
    public function getName(): string
    {
        return trim($this->source['NAME']);
    }

    public function getType(): string
    {
        return trim($this->source['STYPE']);
    }

    /**
     * Returns value units. Empty string if not found
     *
     */
    public function getUnits(): string
    {
        $units = [
            0x01 => 'degrees C',
            0x02 => 'degrees F',
            0x03 => 'degrees K',
            0x04 => 'Volts',
            0x05 => 'Amps',
            0x06 => 'Watts',
            0x07 => 'Joules',
            0x12 => 'RPM',
            0x13 => 'Hz'
        ];
        $unit = hexdec($this->source['UNIT']);
        return isset($units[$unit]) === true ? $units[$unit] : '';
    }

    /**
     * Returns sensor error type
     *
     * Values:
     *  - 0x01 is threshold sensor
     *  - everything else is descrete sensor
     */
    public function getErrorType(): int
    {
        return intval(trim($this->source['ERTYPE']));
    }

    /**
     * Returns sensor value
     *
     * @return float|int
     */
    public function getValue()
    {
        $reading = $this->source['READING'];
        $readingRaw = substr($reading, 0, 2);

        if ($this->isAnalogFormat() === true) {
            $readingRaw = Utils::dechex(Utils::toSigned(Utils::hexdec($readingRaw), 8));
        }

        return Utils::sensorFunc($readingRaw, $this->source['M'], $this->source['B'], $this->source['RB']);
    }

    /**
     * Returns wheras data is in analog format
     *
     */
    public function isAnalogFormat(): bool
    {
        $format = hexdec($this->source['UNIT1']) >> 6;
        return ($format == 0x02 ? true : false);
    }

    /**
     * Returns sensor limit
     *
     * @param string $limit
     * @return float|int
     */
    public function getLimit(string $limit)
    {
        $values = [ 'UNR', 'UC', 'UNC', 'LNR', 'LC', 'LNC' ];
        if (array_search(strtoupper($limit), $values, true) === false) {
            throw new \InvalidArgumentException('Limit value is invalid.');
        }

        $value = $this->source[$limit];

        if ($this->isAnalogFormat() === true) {
            $value = Utils::dechex(Utils::toSigned(Utils::hexdec($value), 8));
        }

        return Utils::sensorFunc($value, $this->source['M'], $this->source['B'], $this->source['RB']);
    }

    /**
     * Returns if sensor is comparable
     *
     */
    public function isComparable(): bool
    {
        $option = Utils::hexdec($this->source['OPTION']);
        if (($option & 0x40) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Returns integer:
     *  - 0: OK (or not available)
     *  - 1: Warning
     *  - 2: Critical
     *  - 3: Unknown
     */
    public function getStatus(): int
    {
        if ($this->isComparable() === false) {
            return 0; // Not available
        }

        if ($this->getErrorType() == 0x01) {
            return $this->getThresholdStatus();
        }

        return $this->getDescreteStatus();
    }

    private function getThresholdStatus(): int
    {
        // Upper Non-recoverable
        if ($this->getValue() > $this->getLimit('UNR')) {
            return 2;
        }

        // Upper Critical
        if ($this->getValue() > $this->getLimit('UC')) {
            return 2;
        }

        // Upper Non-critical
        if ($this->getValue() > $this->getLimit('UNC')) {
            return 1;
        }

        // Lower Non-recoverable
        if ($this->getValue() <= $this->getLimit('LNR')) {
            return 2;
        }

        // Lower Critical
        if ($this->getValue() <= $this->getLimit('LC')) {
            return 2;
        }

        // Lower Non-critical
        if ($this->getValue() <= $this->getLimit('LNC')) {
            return 1;
        }

        return 0;
    }

    private function getDescreteStatus(): int
    {
        $reading = $this->source['READING'];

        $raw = substr($reading, 0, 2);
        $sensorD = intval(substr($reading, 2, 2), 16);
        $sensorDMSB = intval(substr($reading, 4, 2), 16);

        // Linda modified for cpu temp reading
        if (($raw == 0 && $this->getType() != 'c0') && $sensorD == 0 && $sensorDMSB == 0 && $this->getType() != '05') {
            return 0;
        }

        if ($this->getType() == '05') {
            return Utils::showDiscStateAPI('05', (int) $raw);
        }

        if (array_search($this->getType(), ['08', 'c0', 'c2', '0d', '29'], true) !== false) {
            return Utils::showDiscStateAPI($this->getType(), (int) $sensorD);
        }

        // Not supported
        return 3;
    }
}
