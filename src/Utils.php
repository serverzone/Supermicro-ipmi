<?php

namespace ServerZone\SupermicroIpmi;

/**
 * Supermicro utils.js ported functions
 *
 */
class Utils
{

    /**
     * We don't want any instances of this class
     *
     */
    private function __construct()
    {
    }

    /**
     *
     * @param int|string $raw_data Raw data
     * @return int|float
     */
    public static function sensorFunc($raw_data, string $m, string $b, string $rb)
    {
        /* change sequense of lsb and msb into 10b char */
        $M_raw = ((hexdec($m) & 0xC0) << 2) + (hexdec($m) >> 8);
        $B_raw = ((hexdec($b) & 0xC0) << 2) + (hexdec($b) >> 8);

        $Km_raw = hexdec($rb) >> 4;
        $Kb_raw = hexdec($rb) & 0x0F;

        $M_data = self::toSigned($M_raw, 10);
        $B_data = self::toSigned($B_raw, 10);
        $Km_data = self::toSigned($Km_raw, 4);
        $Kb_data = self::toSigned($Kb_raw, 4);

        $sensor_data = ($M_data * self::hexdec($raw_data) + $B_data * pow(10, $Kb_data)) * pow(10, $Km_data);

        return $sensor_data;
    }

    /**
     *
     * @param number $num
     * @return int|float
     */
    public static function toSigned($num, int $signedbitB)
    {
        if ($signedbitB <= 0) {
            return $num;
        }

        // positive
        if (($num % (0x01 << $signedbitB) / (0x01 << ($signedbitB-1)) ) < 1) {
            return $num % (0x01 << $signedbitB - 1);
        }

        // negative
        $temp = ($num % (0x01 << $signedbitB - 1)) ^ ((0x01 << $signedbitB - 1) - 1);
        return ( -1 - $temp);
    }

    public static function showDiscStateAPI(string $type, int $value): int
    {
        if ($type == "05") {
            if ($value == 0) {
                return 0; // OK
            }

            // General Chassis Intrusion.
            // Drive Bay intrusion.
            // I/O Card area intrusion.
            // Prosessor area intrusion.
            // LAN Leash Lost.
            // Unauthorized dock.
            // Fan area intrusion.
            return 2;
        }

        if ($type == "08") {
            if (($value % 2 !== 0) && ($value / 2 % 2 !== 0)) {
                return 0; // Not present / Not supported
            }

            if ($value % 2 !== 0) {
                return 0; // Presence detected
            }

            // Power Supply Failure detected.
            // Predictuve Failure.
            // Power Supply input lost (AC/DC).
            // Power Supply input lost or out-of-range.
            // Power Supply input out-of-range, but present.
            // Configuration error.
            return 2;
        }

        /* Tony, 10/23/2012 add battery backup power { */
        if ($type == "29") {
            if ($value % 2 != 0) {
                return 2; // Battery low (predictive failure).
            }
            if ($value / 2 % 2 != 0) {
                return 2; // Battery failed.
            }
            if ($value / 4 % 2 != 0) {
                return 0; // Battery presence detected.
            }

            return 0;
        }

        // Linda HDD
        if ($type == "0d") {
            if ($value % 2 == 0) {
                return 0; // Drive Presence
            }

            // Drive Fault
            // Predictuve Failure
            // Hot Spare
            // Consistency Check/ Parity Check in progress
            // In Critical Array
            // In Failed Array
            // Rebuid/Remap in progress
            // Rebuid/Remap Aborted
            return 2;
        }

        if ($type == "c0") {
            $values = [
                0 => 0, // Low
                1 => 1, // Medium
                2 => 2, // High
                4 => 2, // Over heat
                8 => 0, // Uninstall
            ];

            if (isset($values[$value]) !== false) {
                return $values[$value];
            }

            return 0; // Not Present!
        }

        if ($type == "c2") {
            if ($value == 0) {
                return 0;
            }

            // None of The Above Fault
            // CML Fault
            // Over Temperature Fault
            // Under Voltage Fault
            // Over Current Fault
            // Over Ovltage Fault
            // PS On/Off
            // Device Busy
            return 2;
        }

        return 3;
    }


    /**
     * Javascript implementation hexdec returns negative number when is negative input is provided
     * PHP returns correct hexadecimal number
     *
     * @param int|string $input
     * @return number
     */
    public static function hexdec($input)
    {
        return ((int) $input < 0 ? (int) $input : hexdec((string) $input));
    }

    /**
     *
     * @param number $input
     * @return int|string
     */
    public static function dechex($input)
    {
        $input = (int) $input;
        return ($input < 0 ? $input : dechex($input));
    }
}
