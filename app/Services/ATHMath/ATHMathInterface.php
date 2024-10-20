<?php

declare(strict_types=1);

namespace App\Services\ATHMath;

interface ATHMathInterface
{
    /**
     * Adds two numbers as strings.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the addition as a string
     */
    public function add(string $num1, string $num2, ?int $scale = null): string;

    /**
     * Compares two numbers as strings.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param ?int $scale Optional number of decimal places for the comparison
     * @return int 0 if the numbers are equal, 1 if num1 > num2, -1 if num1 < num2
     */
    public function compare(string $num1, string $num2, ?int $scale = null): int;

    /**
     * Divides two numbers as strings.
     *
     * @param string $num1 The dividend as a string
     * @param string $num2 The divisor as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the division as a string
     */
    public function divide(string $num1, string $num2, ?int $scale = null): string;

    /**
     * Multiplies two numbers as strings.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the multiplication as a string
     */
    public function multiply(string $num1, string $num2, ?int $scale = null): string;

    /**
     * Returns the modulus of two numbers as strings.
     *
     * @param string $num1 The dividend as a string
     * @param string $num2 The divisor as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the modulus as a string
     */
    public function mod(string $num1, string $num2, ?int $scale = null): string;

    /**
     * Calculates power (exponentiation) for large numbers.
     *
     * @param string $num The base number as a string
     * @param string $exponent The exponent as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of num raised to the power of exponent as a string
     */
    public function power(string $num, string $exponent, ?int $scale = null): string;

    /**
     * Calculates modular exponentiation for large numbers.
     *
     * @param string $num The base number as a string
     * @param string $exponent The exponent as a string
     * @param string $modulus The modulus as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of (num^exponent) % modulus as a string
     */
    public function powMod(string $num, string $exponent, string $modulus, ?int $scale = null): string;

    /**
     * Sets or gets the global scale used in operations.
     * If no scale is provided, returns the current scale.
     *
     * @param ?int $scale Optional scale to set globally
     * @return int The current or newly set scale
     */
    public function scale(?int $scale = null): int;

    /**
     * Calculates the square root of a number.
     *
     * @param string $num The number to calculate the square root of as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The square root of the number as a string
     */
    public function sqrt(string $num, ?int $scale = null): string;

    /**
     * Subtracts two numbers as strings.
     *
     * @param string $num1 The first number as a string (minuend)
     * @param string $num2 The second number as a string (subtrahend)
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the subtraction as a string
     */
    public function subtract(string $num1, string $num2, ?int $scale = null): string;
}
