<?php

declare(strict_types=1);

namespace App\Services\ATHMath;

class ATHMath implements ATHMathInterface
{
    // Property for the global scale
    private $globalScale = 0; // Default scale

    // Public Methods

    /**
     * {@inheritDoc}
     */
    public function ath_add(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->ath_bcadd($num1, $num2, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function compare(string $num1, string $num2, ?int $scale = null): int
    {
        return $this->ath_comp($num1, $num2, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function divide(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->ath_div($num1, $num2, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function multiply(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->ath_mul($num1, $num2, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function mod(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->ath_mod($num1, $num2, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function power(string $num, string $exponent, ?int $scale = null): string
    {
        return $this->ath_pow($num, $exponent, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function powMod(string $num, string $exponent, string $modulus, ?int $scale = null): string
    {
        return $this->ath_powmod($num, $exponent, $modulus, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function scale(?int $scale = null): int
    {
        return $this->ath_scale($scale);
    }

    /**
     * {@inheritDoc}
     */
    public function sqrt(string $num, ?int $scale = null): string
    {
        return $this->ath_sqrt($num, $scale);
    }

    /**
     * {@inheritDoc}
     */
    public function subtract(string $num1, string $num2, ?int $scale = null): string
    {
        return $this->ath_sub($num1, $num2, $scale);
    }

    /**
     * -------------------
     * * Private Methods *
     * -------------------
     */

    /**
     * Private method that adds two arbitrarily large numbers as strings.
     * Simulates the behavior of bcadd.
     */

    /**
     * Returns the absolute value of a number as a string.
     *
     * This function takes a number represented as a string and returns its absolute value.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The absolute value of the given number (as a string).
     */
    protected function ath_abs(string $num): string
    {
        // Check if the number is negative, if so, return its positive counterpart.
        // BC Math functions expect strings for arbitrary precision numbers.
        return $this->ath_comp($num, '0') < 0 ? $this->ath_sub('0', $num) : $num;
    }

    /**
     * Calculates the arc cosine of a number.
     *
     * This function takes a number between -1 and 1 and computes its arccosine
     * using an approximation method. It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The arc cosine of the given number (as a string).
     * @throws \InvalidArgumentException If the input is not between -1 and 1.
     */
    protected function ath_acos(string $num): string
    {
        // Approximation method to calculate arccos without native PHP functions.
        // Here we use a polynomial approximation or series expansion for acos.

        // Convert string input to float for comparison
        $num_float = (float)$num;

        if ($num_float < -1.0 || $num_float > 1.0) {
            throw new \InvalidArgumentException("Input must be between -1 and 1");
        }

        // Using acos(x) = pi/2 - asin(x), approximating asin with series expansion
        $x = $num_float;
        $pi = A_PI; // Approximation of pi
        $asin = $x; // Initial value for arcsin approximation

        // Polynomial expansion for arcsin
        $asin += ($x * $x * $x) / 6.0;
        $asin += (3 * $this->ath_pow((string)$x, '5')) / 40.0;

        return (string)($pi / 2 - $asin); // Return result as a string
    }

    /**
     * Calculates the inverse hyperbolic cosine of a number.
     *
     * This function takes a number greater than or equal to 1 and computes its
     * inverse hyperbolic cosine using logarithmic properties. It does not use
     * native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The inverse hyperbolic cosine of the given number (as a string).
     * @throws \InvalidArgumentException If the input is less than 1.
     */
    protected function ath_acosh(string $num): string
    {
        // Check if the input is a valid number
        $num_float = (float)$num;

        if ($num_float < 1.0) {
            throw new \InvalidArgumentException("Input must be greater than or equal to 1");
        }

        // Approximate acosh(x) using logarithmic properties:
        // acosh(x) = ln(x + sqrt(x^2 - 1))
        $x = $num_float;
        $sqrtPart = $this->ath_sqrt(($this->ath_pow((string)$x, '2')) . ' - 1'); // Using ath_pow for exponentiation
        return $this->ath_log($this->ath_bcadd((string)$x, $sqrtPart)); // Using ath_bcadd for addition
    }

    protected function ath_bcadd(string $num1, string $num2, ?int $scale = null): string
    {
        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $max_frac_length = max(strlen($num1_frac), strlen($num2_frac));
        $num1_frac = str_pad($num1_frac, $max_frac_length, '0', STR_PAD_RIGHT);
        $num2_frac = str_pad($num2_frac, $max_frac_length, '0', STR_PAD_RIGHT);

        $frac_sum = $this->addStrings($num1_frac, $num2_frac);

        if (strlen($frac_sum) > $max_frac_length) {
            $carry = substr($frac_sum, 0, 1);
            $frac_sum = substr($frac_sum, 1);
            $num1_int = $this->addStrings($num1_int, $carry);
        }

        $int_sum = $this->addStrings($num1_int, $num2_int);
        $result = $int_sum;

        if ($max_frac_length > 0) {
            $result .= '.' . $frac_sum;
        }

        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Calculates the inverse sine of a number.
     *
     * This function takes a number between -1 and 1 and computes its inverse sine
     * using a Taylor series expansion. It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The inverse sine of the given number (as a string).
     * @throws InvalidArgumentException If the input is not between -1 and 1.
     */
    protected function ath_asin(string $num): string
    {
        // Check if the input is a valid number
        $num_float = (float)$num;

        if ($num_float < -1.0 || $num_float > 1.0) {
            throw new \InvalidArgumentException("Input must be between -1 and 1");
        }

        // Approximation using a Taylor series expansion for asin(x)
        $x = $num_float;
        $result = $x; // Start with the first term of the Taylor series

        $term = $x; // Initialize the first term
        for ($n = 1; $n < 10; $n++) {
            $term *= ($x * $x * (2 * $n - 1)) / (2 * $n);
            $result += $term / (2 * $n + 1);
        }

        return (string)$result; // Return the result as a string
    }

    /**
     * Calculates the inverse hyperbolic sine of a number.
     *
     * This function takes a number and computes its inverse hyperbolic sine
     * using logarithmic properties. It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The inverse hyperbolic sine of the given number (as a string).
     */
    protected function ath_asinh(string $num): string
    {
        // Ensure the input is valid
        if ($this->ath_is_nan($num)) {
            throw new \InvalidArgumentException("Input must be a valid number.");
        }

        // Use the formula: asinh(x) = ln(x + sqrt(x^2 + 1))
        $x_squared = ($this->ath_mul($num, $num)); // x^2
        $sqrtPart = $this->ath_sqrt($this->ath_bcadd($x_squared, '1')); // sqrt(x^2 + 1)

        return $this->ath_log($this->ath_bcadd($num, $sqrtPart)); // ln(x + sqrt(x^2 + 1))
    }


    /**
     * Calculates the inverse tangent of a number.
     *
     * This function takes a number and computes its inverse tangent using
     * a Taylor series expansion. It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The inverse tangent of the given number (as a string).
     */
    protected function ath_atan(string $num): string
    {
        // Approximation of atan using Taylor series:
        // atan(x) = x - (x^3 / 3) + (x^5 / 5) - (x^7 / 7) + ...

        // Convert string input to float for calculations
        $x = (float)$num;
        $result = 0.0;
        $sign = 1;

        for ($n = 1; $n < 100; $n += 2) {
            // Calculate the term using ath_pow for power and adjust division
            $term = $this->ath_pow($num, (string)$n);
            $term = $this->ath_div($term, (string)$n);
            $result = $this->ath_bcadd((string)$result, ($sign === 1 ? $term : $this->ath_sub('0', $term))); // Add or subtract
            $sign = -$sign; // Alternate the sign
        }

        return (string)$result; // Return the result as a string
    }

    /**
     * Calculates the inverse tangent of the quotient of two numbers (y/x),
     * considering the signs of both to determine the correct quadrant.
     *
     * This function takes two numbers and computes the arctangent using the signs
     * of the arguments to determine the appropriate quadrant. It does not use
     * native PHP functions for the calculation.
     *
     * @param string $y The y-coordinate (as a string).
     * @param string $x The x-coordinate (as a string).
     * @return string The arctangent of the two coordinates (as a string).
     */
    protected function ath_atan2(string $y, string $x): string
    {
        // atan2 implementation based on quadrant identification
        $pi = (string)A_PI; // Approximation of pi

        // Convert string inputs to float for calculations
        $y_float = (float)$y;
        $x_float = (float)$x;

        if ($x_float > 0) {
            return $this->ath_atan($y); // Use the custom atan function
        }

        if ($x_float < 0 && $y_float >= 0) {
            return $this->ath_bcadd($this->ath_atan($y), $pi);
        }

        if ($x_float < 0 && $y_float < 0) {
            return $this->ath_sub($this->ath_atan($y), $pi);
        }

        if ($x_float == 0 && $y_float > 0) {
            return $this->ath_div($pi, '2'); // Return pi/2
        }

        if ($x_float == 0 && $y_float < 0) {
            return $this->ath_sub('0', $this->ath_div($pi, '2')); // Return -pi/2
        }

        // atan2(0, 0) is usually defined as 0
        return '0';
    }

    /**
     * Calculates the inverse hyperbolic tangent of a number.
     *
     * This function takes a number between -1 and 1 (exclusive) and computes its
     * inverse hyperbolic tangent using logarithmic properties. It does not use
     * native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The inverse hyperbolic tangent of the given number (as a string).
     * @throws InvalidArgumentException If the input is not between -1 and 1 (exclusive).
     */
    protected function ath_atanh(string $num): string
    {
        // Validate input to ensure it is between -1 and 1 (exclusive)
        $num_float = (float)$num;

        if ($num_float <= -1.0 || $num_float >= 1.0) {
            throw new \InvalidArgumentException("Input must be between -1 and 1 (exclusive)");
        }

        // Calculate atanh(x) = 0.5 * ln((1 + x) / (1 - x))
        $one_plus_x = $this->ath_bcadd($num, '1'); // 1 + x
        $one_minus_x = $this->ath_sub('1', $num); // 1 - x
        $ln_argument = $this->ath_div($one_plus_x, $one_minus_x); // (1 + x) / (1 - x)

        return $this->ath_div($this->ath_log($ln_argument), '2'); // 0.5 * ln(...)
    }

    /**
     * Converts a number from one base to another.
     *
     * This function takes a number in a specified base and converts it to another base.
     * It supports bases from 2 to 36 and does not use native PHP functions for the conversion.
     *
     * @param string $num The number to convert (as a string).
     * @param int $from_base The base of the input number.
     * @param int $to_base The base to convert the number to.
     * @return string The converted number (as a string).
     * @throws InvalidArgumentException If the base is not between 2 and 36 or if an invalid character is found for the base.
     */
    protected function ath_base_convert(string $num, int $from_base, int $to_base): string
    {
        // Validate base values
        if ($from_base < 2 || $from_base > 36 || $to_base < 2 || $to_base > 36) {
            throw new \InvalidArgumentException("Base must be between 2 and 36");
        }

        // Convert input number to decimal (base 10)
        $decimal_value = '0'; // Use string to avoid precision issues
        $length = strlen($num);

        for ($i = 0; $i < $length; $i++) {
            $digit = $num[$length - $i - 1];
            $value = ctype_digit($digit) ? (string)intval($digit) : (string)(ord(strtolower($digit)) - ord('a') + 10);

            if ((int)$value >= $from_base) {
                throw new \InvalidArgumentException("Invalid character '$digit' for base $from_base");
            }

            // Multiply the value by from_base^i and add to the total decimal value
            $decimal_value = $this->ath_bcadd($decimal_value, $this->ath_mul($value, $this->ath_pow((string)$from_base, (string)$i)));
        }

        // Convert decimal value to the target base
        if ($decimal_value === '0') {
            return '0';
        }

        $result = '';
        while ($this->ath_comp($decimal_value, '0') > 0) {
            // Calculate the remainder when dividing by to_base
            $remainder = $this->ath_mod($decimal_value, (string)$to_base);
            $value = (int)$remainder < 10 ? $remainder : chr((int)$remainder + ord('a') - 10);
            $result = $value . $result;

            // Update decimal value by dividing by to_base
            $decimal_value = $this->ath_div($decimal_value, (string)$to_base, 0); // No decimals
        }

        return $result;
    }

    /**
     * Converts a binary string to its decimal equivalent.
     *
     * This function takes a binary string (composed of 0s and 1s) and converts it to a decimal integer.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $binary_string The binary string to convert.
     * @return string The decimal equivalent of the binary string (as a string).
     * @throws InvalidArgumentException If the input is not a valid binary string.
     */
    protected function ath_bindec(string $binary_string): string
    {
        // Validate input to ensure it's a valid binary string
        if (!preg_match('/^[01]+$/', $binary_string)) {
            throw new \InvalidArgumentException("Input must be a binary string (only contains 0s and 1s)");
        }

        $decimal_value = '0'; // Use string for arbitrary precision
        $length = strlen($binary_string);

        // Convert binary string to decimal
        for ($i = 0; $i < $length; $i++) {
            $bit = $binary_string[$length - $i - 1];
            if ($bit === '1') {
                // Add 2^i to the decimal value
                $decimal_value = $this->ath_bcadd($decimal_value, $this->ath_pow('2', (string)$i));
            }
        }

        return $decimal_value; // Return the result as a string
    }

    /**
     * Returns the ceiling of a number.
     *
     * This function takes a number as a string and returns the smallest integer
     * greater than or equal to the number. It does not use native PHP functions
     * for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The ceiling of the given number (as a string).
     */
    protected function ath_ceil(string $num): string
    {
        // Split the number into integer and fractional parts
        list($intPart, $fracPart) = explode('.', $num . '.0'); // Add '.0' to handle integers

        // If the fractional part is greater than 0, add 1 to the integer part
        if ($this->ath_comp('0', $fracPart) < 0) {
            return $this->ath_bcadd($intPart, '1');
        }

        // Return the integer part as the ceiling if no fractional part
        return $intPart;
    }

    /**
     * Private method that compares two arbitrarily large numbers as strings.
     * Simulates the behavior of bccomp.
     */
    protected function ath_comp(string $num1, string $num2, ?int $scale = null): int
    {
        if ($scale !== null) {
            $num1 = $this->adjustScale($num1, $scale);
            $num2 = $this->adjustScale($num2, $scale);
        }

        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $int_comparison = $this->compareStrings($num1_int, $num2_int);
        if ($int_comparison !== 0) {
            return $int_comparison;
        }

        $max_frac_length = max(strlen($num1_frac), strlen($num2_frac));
        $num1_frac = str_pad($num1_frac, $max_frac_length, '0', STR_PAD_RIGHT);
        $num2_frac = str_pad($num2_frac, $max_frac_length, '0', STR_PAD_RIGHT);

        return $this->compareStrings($num1_frac, $num2_frac);
    }

    /**
     * Calculates the cosine of a number.
     *
     * This function takes an angle in radians (as a string) and computes the cosine
     * using a Taylor series expansion. It does not use native PHP functions for the calculation.
     *
     * @param string $num The angle in radians (as a string).
     * @return string The cosine of the given angle (as a string).
     */
    protected function ath_cos(string $num): string
    {
        // Cosine can be computed using the Taylor series expansion:
        // cos(x) = 1 - (x^2 / 2!) + (x^4 / 4!) - (x^6 / 6!) + ...

        // Normalize the angle to the range [0, 2π]
        $two_pi = $this->ath_mul('2', (string)A_PI); // 2π
        $num = $this->ath_mod($num, $two_pi); // num mod 2π

        $result = '1'; // First term of the series (cos(0) = 1)
        $term = '1';   // To hold each term in the series
        $n = 1;

        while ($n < 24) { // Using 24 terms for better precision
            // Calculate the next term: (-1)^n * (x^2n) / (2n)!
            $term = $this->ath_mul($term, $this->ath_mul($this->ath_sub('0', $num), $num));
            $term = $this->ath_div($term, $this->ath_mul((string)(2 * $n), (string)(2 * $n - 1)));

            // Add the term to the result
            $result = $this->ath_bcadd($result, $term);
            $n++;
        }

        return $result; // Return the result as a string
    }

    /**
     * Calculates the hyperbolic cosine of a number.
     *
     * This function takes a number (as a string) and computes its hyperbolic cosine
     * using the formula: cosh(x) = (e^x + e^(-x)) / 2. It does not use native PHP functions
     * for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The hyperbolic cosine of the given number (as a string).
     */
    protected function ath_cosh(string $num): string
    {
        // cosh(x) = (e^x + e^(-x)) / 2

        // Calculate e^x
        $exp_x = $this->ath_exp($num);

        // Calculate e^(-x) which is 1 / e^x
        $exp_neg_x = $this->ath_div('1', $exp_x);

        // Sum the two values and divide by 2
        $sum = $this->ath_bcadd($exp_x, $exp_neg_x);

        return $this->ath_div($sum, '2');
    }

    /**
     * Converts a decimal number to its binary representation.
     *
     * This function takes a non-negative decimal number (as a string) and converts it to its binary representation.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $num The decimal number to convert (as a string).
     * @return string The binary representation of the given number (as a string).
     * @throws InvalidArgumentException If the input is not a non-negative integer.
     */
    protected function ath_decbin(string $num): string
    {
        // Validate input to ensure it's a non-negative integer
        if ($this->ath_comp($num, '0') < 0) {
            throw new \InvalidArgumentException("Input must be a non-negative integer.");
        }

        if (
            $this->ath_comp($num, '0') === 0
        ) {
            return '0'; // The binary representation of 0 is "0"
        }

        $binary_string = '';

        // Convert decimal to binary
        while ($this->ath_comp($num, '0') > 0) {
            // Append the remainder of the division by 2
            $remainder = $this->ath_mod($num, '2');
            $binary_string = $remainder . $binary_string;

            // Divide the number by 2
            $num = $this->ath_div(
                $num,
                '2',
                0
            ); // No fractional part
        }

        return $binary_string;
    }

    /**
     * Converts a decimal number to its hexadecimal representation.
     *
     * This function takes a non-negative decimal number (as a string) and converts it to its hexadecimal representation.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $num The decimal number to convert (as a string).
     * @return string The hexadecimal representation of the given number (as a string).
     * @throws InvalidArgumentException If the input is not a non-negative integer.
     */
    protected function ath_dechex(string $num): string
    {
        // Validate input to ensure it's a non-negative integer
        if ($this->ath_comp($num, '0') < 0) {
            throw new \InvalidArgumentException("Input must be a non-negative integer.");
        }

        if (
            $this->ath_comp($num, '0') === 0
        ) {
            return '0'; // The hexadecimal representation of 0 is "0"
        }

        $hex_string = '';

        // Convert decimal to hexadecimal
        while ($this->ath_comp($num, '0') > 0) {
            // Get the remainder when dividing by 16
            $remainder = $this->ath_mod($num, '16');
            $remainder_int = (int)$remainder;

            // Convert remainder to hexadecimal character
            if ($remainder_int < 10) {
                $hex_string = $remainder . $hex_string; // Add number (0-9)
            } else {
                // Add letter (a-f)
                $hex_string = chr($remainder_int + 87) . $hex_string; // Convert 10-15 to 'a'-'f'
            }

            // Divide the number by 16
            $num = $this->ath_div(
                $num,
                '16',
                0
            ); // Update the number with no fractional part
        }

        return $hex_string;
    }

    /**
     * Converts a decimal number to its octal representation.
     *
     * This function takes a non-negative decimal number (as a string) and converts it to its octal representation.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $num The decimal number to convert (as a string).
     * @return string The octal representation of the given number (as a string).
     * @throws InvalidArgumentException If the input is not a non-negative integer.
     */
    protected function ath_decoct(string $num): string
    {
        // Validate input to ensure it's a non-negative integer
        if ($this->ath_comp($num, '0') < 0) {
            throw new \InvalidArgumentException("Input must be a non-negative integer.");
        }

        if (
            $this->ath_comp($num, '0') === 0
        ) {
            return '0'; // The octal representation of 0 is "0"
        }

        $octal_string = '';

        // Convert decimal to octal
        while ($this->ath_comp($num, '0') > 0) {
            // Get the remainder when dividing by 8
            $remainder = $this->ath_mod($num, '8');
            $octal_string = $remainder . $octal_string; // Prepend the remainder
            $num = $this->ath_div(
                $num,
                '8',
                0
            ); // Update the number with no fractional part
        }

        return $octal_string;
    }

    /**
     * Converts degrees to radians.
     *
     * This function takes an angle in degrees (as a string) and converts it to radians.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $num The angle in degrees (as a string).
     * @return string The angle in radians (as a string).
     */
    protected function ath_deg2rad(string $num): string
    {
        // Approximate value of π as a string
        $pi = (string)A_PI;

        // Convert degrees to radians: radians = degrees * (π / 180)
        $factor = $this->ath_div($pi, '180'); // π / 180
        return $this->ath_mul($num, $factor); // degrees * (π / 180)
    }

    /**
     * Private method that divides two arbitrarily large numbers as strings.
     * Simulates the behavior of bcdiv.
     */
    protected function ath_div(string $num1, string $num2, ?int $scale = null): string
    {
        if ($num2 === '0' || $num2 === '0.0') {
            throw new \Exception('Division by zero');
        }

        if ($scale === null) {
            $scale = 10;
        }

        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $num1_full = $num1_int . $num1_frac;
        $num2_full = $num2_int . $num2_frac;

        $scale_factor = strlen($num1_frac) - strlen($num2_frac);

        if ($scale_factor > 0) {
            $num2_full = str_pad($num2_full, strlen($num2_full) + $scale_factor, '0');
        } elseif ($scale_factor < 0) {
            $num1_full = str_pad($num1_full, strlen($num1_full) - $scale_factor, '0');
        }

        $quotient = $this->longDivision($num1_full, $num2_full);
        $quotient = $this->insertDecimalPoint($quotient, $scale);

        return $this->adjustScale($quotient, $scale);
    }

    /**
     * Calculates the exponential of a number (e^x) using Taylor series.
     *
     * This function computes e raised to the power of the given number using
     * the Taylor series expansion. It does not use native PHP functions for the calculation.
     *
     * @param string $num The exponent (as a string).
     * @return string The value of e^x (as a string).
     */
    protected function ath_exp(string $num): string
    {
        // Calculate e^x using the Taylor series expansion:
        // exp(x) = 1 + (x / 1!) + (x^2 / 2!) + (x^3 / 3!) + ...

        $result = '1'; // First term of the series
        $term = '1';   // To hold each term in the series
        $n = 1;

        // Use 24 terms for better precision
        while ($n <= 24) {
            // Calculate the next term: term = term * (x / n)
            $term = $this->ath_mul($term, $this->ath_div($num, (string)$n));

            // Add the term to the result
            $result = $this->ath_bcadd($result, $term);

            // Increment the counter
            $n++;
        }

        return $result; // Return the value of e^x
    }

    /**
     * Calculates e^x - 1 using Taylor series.
     *
     * This function computes e raised to the power of the given number minus 1,
     * using the Taylor series expansion. It does not use native PHP functions
     * for the calculation.
     *
     * @param string $num The exponent (as a string).
     * @return string The value of e^x - 1 (as a string).
     */
    protected function ath_expm1(string $num): string
    {
        // Special case for x = 0
        if ($this->ath_comp($num, '0') === 0) {
            return '0'; // expm1(0) = 0
        }

        // Calculate e^x - 1 using the Taylor series expansion:
        // expm1(x) = x + (x^2 / 2!) + (x^3 / 3!) + ...

        $result = $num; // Start with the first term (x)
        $term = $num;   // To hold each term in the series
        $n = 1;

        // Use 24 terms for better precision
        while ($n < 24) {
            // Calculate the next term: term = term * (x / (n + 1))
            $term = $this->ath_mul($term, $this->ath_div($num, (string)($n + 1)));

            // Add the term to the result
            $result = $this->ath_bcadd($result, $term);

            // Increment the counter
            $n++;
        }

        return $result; // Return the result of e^x - 1
    }

    /**
     * Performs division between two numbers.
     *
     * This function takes two numbers and performs division. It checks for division by zero.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num1 The dividend (as a string).
     * @param string $num2 The divisor (as a string).
     * @return string The result of the division (as a string).
     * @throws InvalidArgumentException If the divisor is zero.
     */
    protected function ath_fdiv(string $num1, string $num2): string
    {
        // Check for division by zero
        if (
            $this->ath_comp($num2, '0') === 0
        ) {
            throw new \InvalidArgumentException("Division by zero is not allowed.");
        }

        // Perform the division
        return $this->ath_div($num1, $num2);
    }

    /**
     * Returns the floor of a number.
     *
     * This function takes a number as a string and returns the largest integer less than
     * or equal to the number. It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The floor of the given number (as a string).
     */
    protected function ath_floor(string $num): string
    {
        // Split the number into integer and fractional parts
        list($intPart, $fracPart) = explode('.', $num . '.0'); // Add '.0' to handle integers

        // If the number is negative and has a fractional part, subtract 1 from the integer part
        if ($this->ath_comp($intPart, '0') < 0 && $this->ath_comp($fracPart, '0') > 0) {
            return $this->ath_sub($intPart, '1');
        }

        // Return the integer part as the floor value
        return $intPart;
    }

    /**
     * Calculates the floating-point remainder of dividing two numbers.
     *
     * This function takes two numbers and computes the remainder of the division of the first number by the second.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num1 The dividend (as a string).
     * @param string $num2 The divisor (as a string).
     * @return string The remainder (modulo) of the division (as a string).
     * @throws InvalidArgumentException If the divisor is zero.
     */
    protected function ath_fmod(string $num1, string $num2): string
    {
        // Check for division by zero
        if (
            $this->ath_comp($num2, '0') === 0
        ) {
            throw new \InvalidArgumentException("Division by zero is not allowed.");
        }

        // Calculate the modulus using the floor function
        // fmod(num1, num2) = num1 - floor(num1 / num2) * num2
        $division = $this->ath_div($num1, $num2);
        $floor_division = $this->ath_floor($division);
        $product = $this->ath_mul($floor_division, $num2);

        return $this->ath_sub($num1, $product);
    }

    /**
     * Converts a hexadecimal string to its decimal equivalent.
     *
     * This function takes a valid hexadecimal string and converts it to a decimal number.
     * It does not use native PHP functions for the conversion.
     *
     * @param string $hex_string The hexadecimal string to convert.
     * @return string The decimal equivalent of the given hexadecimal string (as a string).
     * @throws InvalidArgumentException If the input is not a valid hexadecimal string.
     */
    protected function ath_hexdec(string $hex_string): string
    {
        // Validate input to ensure it's a valid hexadecimal string
        if (!preg_match('/^[0-9a-fA-F]+$/', $hex_string)) {
            throw new \InvalidArgumentException("Input must be a valid hexadecimal string.");
        }

        $decimal_value = '0'; // Initialize as string for BC Math operations
        $length = strlen($hex_string);

        // Convert hexadecimal string to decimal
        for ($i = 0; $i < $length; $i++) {
            $digit = $hex_string[$length - $i - 1];
            $value = ctype_digit($digit) ? (string)intval($digit) : (string)(ord(strtolower($digit)) - ord('a') + 10);

            // Calculate value * (16^i) and add it to the decimal value
            $decimal_value = $this->ath_bcadd($decimal_value, $this->ath_mul($value, $this->ath_pow('16', (string)$i)));
        }

        return $decimal_value;
    }

    /**
     * Calculates the hypotenuse of a right-angled triangle using the formula: sqrt(x^2 + y^2).
     *
     * This function takes two numbers and computes the hypotenuse (the length of the longest side of the triangle).
     * It does not use native PHP functions for the calculation.
     *
     * @param string $x The length of one side of the triangle (as a string).
     * @param string $y The length of the other side of the triangle (as a string).
     * @return string The length of the hypotenuse (as a string).
     */
    protected function ath_hypot(string $x, string $y): string
    {
        // Calculate x^2 and y^2
        $x_squared = $this->ath_pow(
            $x,
            '2'
        );
        $y_squared = $this->ath_pow(
            $y,
            '2'
        );

        // Calculate the sum of x^2 + y^2
        $sum = $this->ath_bcadd($x_squared, $y_squared);

        // Return the square root of the sum
        return $this->ath_sqrt($sum);
    }

    /**
     * Performs integer division between two numbers.
     *
     * This function takes two integers and performs integer division, removing any fractional part.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num1 The dividend (as a string).
     * @param string $num2 The divisor (as a string).
     * @return string The result of the integer division (as a string).
     * @throws InvalidArgumentException If the divisor is zero.
     */
    protected function ath_intdiv(string $num1, string $num2): string
    {
        // Check for division by zero
        if (
            $this->ath_comp($num2, '0') === 0
        ) {
            throw new \InvalidArgumentException("Division by zero is not allowed.");
        }

        // Perform integer division
        // Divide the numbers and remove the fractional part using ath_floor
        $division = $this->ath_div($num1, $num2, 0);
        return $this->ath_floor($division); // Ensure integer result
    }

    /**
     * Checks whether a number is finite.
     *
     * This function determines whether a number is finite, meaning it is not infinite
     * and not NaN (Not a Number). It does not use native PHP functions for the check.
     *
     * @param string $num The number to check (as a string).
     * @return bool True if the number is finite, false otherwise.
     */
    protected function ath_is_finite(string $num): bool
    {
        // Define a large number to simulate infinity for comparison
        $infinity = '1e1000'; // An arbitrary large number to simulate infinity

        // Check if the number is not infinite (positive or negative) and not NaN
        return $this->ath_comp($num, $infinity) < 0 && $this->ath_comp($num, '-' . $infinity) > 0;
    }

    /**
     * Checks whether a number is infinite.
     *
     * This function determines whether a number is infinite, meaning it is larger than
     * a certain threshold or smaller than a negative threshold (simulating infinity).
     * It does not use native PHP functions for the check.
     *
     * @param string $num The number to check (as a string).
     * @return bool True if the number is infinite, false otherwise.
     */
    protected function ath_is_infinite(string $num): bool
    {
        // Define a large number to simulate infinity for comparison
        $infinity = '1e1000'; // An arbitrary large number to simulate infinity

        // Check if the number is equal to or greater than infinity or equal to or less than negative infinity
        return $this->ath_comp($num, $infinity) >= 0 || $this->ath_comp($num, '-' . $infinity) <= 0;
    }

    /**
     * Checks whether a string is NaN (Not a Number).
     *
     * This function checks if the input string is a valid numerical representation
     * since BC Math only handles valid numerical strings. It returns true if the
     * input is not a valid number.
     *
     * @param string $num The number to check (as a string).
     * @return bool True if the input is not a valid number (NaN), false otherwise.
     */
    protected function ath_is_nan(string $num): bool
    {
        // Check if the input is a valid number (BC Math only handles valid numerical strings)
        return !preg_match('/^-?\d+(\.\d+)?$/', $num); // Return true if the string is not a valid number
    }

    /**
     * Calculates the natural logarithm (ln) of a number using the Taylor series expansion.
     *
     * This function computes the natural logarithm of a number, approximating ln(x)
     * for values 0 < x <= 2 using the Taylor series expansion. If the number is greater than 2,
     * it reduces the input using logarithmic properties and then applies the series.
     *
     * @param string $num The number (as a string) for which to calculate the natural logarithm.
     * @return string The natural logarithm of the number (as a string).
     */
    protected function ath_ln(string $num): string
    {
        // ln(x) using series expansion is valid for 0 < x <= 2
        if ($this->ath_comp($num, '1') === 0) {
            return '0'; // ln(1) = 0
        }

        // If the number is greater than 2, reduce it with log properties and then use the series
        $log = '0'; // Initialize the log value
        while (
            $this->ath_comp($num, '2') > 0
        ) {
            // Reduce the number by dividing it by 2 and adding ln(2) to the result
            $num = $this->ath_div($num, '2');
            $log = $this->ath_bcadd($log, '0.6931471805599453');  // Approximation of ln(2)
        }

        // Use the Taylor series expansion for ln(x) around x = 1
        $x_minus_1 = $this->ath_sub($num, '1');  // Calculate (x - 1)
        $term = $x_minus_1;  // Initialize the first term in the series
        $log_approx = $term;  // Start the approximation with the first term
        $sign = -1;  // The sign alternates for each term in the series

        // Iterate through the series to improve the approximation
        for ($n = 2; $n <= 20; $n++) {
            // Calculate the next term in the Taylor series: (x - 1)^n / n
            $term = $this->ath_mul($term, $x_minus_1);  // (x - 1)^n
            $term_divided = $this->ath_div($term, (string)$n);  // (x - 1)^n / n
            // Add or subtract the term depending on the sign
            $log_approx = ($sign === 1) ? $this->ath_bcadd($log_approx, $term_divided) : $this->ath_sub($log_approx, $term_divided);
            $sign *= -1;  // Alternate the sign for the next term
        }

        // Return the final result by adding the approximation to the reduced log value
        return $this->ath_bcadd($log, $log_approx);
    }

    /**
     * Calculates the base-10 logarithm (log10) of a number.
     *
     * This function calculates the base-10 logarithm of a number using the change of base formula.
     * It does not use native PHP functions, but instead relies on the natural logarithm (ln) method.
     *
     * @param string $num The number for which to calculate log10 (as a string).
     * @return string The base-10 logarithm of the number (as a string).
     * @throws \InvalidArgumentException If the input is not a positive number.
     */
    protected function ath_log10(string $num): string
    {
        // Validate input to ensure it's a positive number
        if ($this->ath_comp($num, '0') <= 0) {
            throw new \InvalidArgumentException("Input must be a positive number.");
        }

        // Use the change of base formula: log10(x) = log_e(x) / log_e(10)
        $log_num = $this->ath_log($num); // Calculate the natural logarithm of the number
        $log_10 = $this->ath_log('10');  // Calculate the natural logarithm of 10

        // Return log10(num) = log_e(num) / log_e(10)
        return $this->ath_div($log_num, $log_10);
    }

    /**
     * Approximates the natural logarithm (ln) of a number using series expansion.
     * The approximation is based on the Taylor series for ln(x) around x = 1.
     *
     * @param string $num The number (as a string) for which to calculate the logarithm
     * @param int $precision The number of terms to use in the series expansion (default: 20)
     * @return string The natural logarithm of the number as a string
     * @throws \Exception If the number is less than or equal to 0
     */
    protected function ath_log(string $num, int $precision = 20): string
    {
        // Convert the input to a float for initial check
        if ($this->ath_comp($num, '0') <= 0) {
            throw new \Exception('Logarithm is undefined for numbers less than or equal to zero.');
        }

        // If the number is exactly 1, return 0 (log(1) = 0)
        if ($this->ath_comp($num, '1') === 0) {
            return '0';
        }

        // If the number is very close to 1, use a series approximation
        $epsilon = '1e-6';
        if ($this->ath_comp($num, $this->ath_bcadd('1', $epsilon)) <= 0 && $this->ath_comp($num, bcsub('1', $epsilon)) >= 0) {
            return $this->taylorLogarithm($num, $precision);
        }

        // For numbers larger or smaller than 1, transform using log(x) = log(x/e) + 1
        // Reduce the input using log rules
        $log = '0';
        while ($this->ath_comp($num, '1') > 0) {
            $num = $this->ath_div($num, '2');
            $log = $this->ath_bcadd($log, '0.69314718055995');  // Approximation of log(2)
        }
        while ($this->ath_comp($num, '0.5') < 0) {
            $num = $this->ath_mul($num, '2');
            $log = $this->ath_sub($log, '0.69314718055995');
        }

        return $this->ath_bcadd($log, $this->taylorLogarithm($num, $precision));
    }

    /**
     * Calculates log(1 + x), the natural logarithm of (1 + x).
     *
     * This function calculates log(1 + x) to avoid precision issues for small values of x.
     * It uses the natural logarithm method and does not rely on native PHP functions.
     *
     * @param string $num The number for which to calculate log(1 + x) (as a string).
     * @return string The natural logarithm of (1 + x) (as a string).
     * @throws \InvalidArgumentException If the input is less than or equal to -1.
     */
    protected function ath_log1p(string $num): string
    {
        // Validate input to ensure it's greater than -1
        if (
            $this->ath_comp($num, '-1') <= 0
        ) {
            throw new \InvalidArgumentException("Input must be greater than -1.");
        }

        // Calculate log1p(x) = log(1 + x), using the natural logarithm
        $one_plus_num = $this->ath_bcadd(
            '1',
            $num
        );  // 1 + x
        return $this->ath_log($one_plus_num);  // log(1 + x)
    }

    /**
     * Calculates the natural logarithm (ln) of a number using BC Math functions.
     *
     * This function computes the natural logarithm of a number using a series expansion
     * for ln((1+x)/(1-x)). It does not use native PHP functions for the calculation.
     *
     * @param string $num The input number (as a string).
     * @return string The natural logarithm of the given number (as a string).
     * @throws \InvalidArgumentException If the input is not positive.
     */
    protected function ath_log_e(string $num): string
    {
        // Validate input to ensure it's a positive number
        if ($this->ath_comp($num, '0') <= 0) {
            throw new \InvalidArgumentException("Input must be a positive number.");
        }

        $result = '0'; // Initialize result as a string
        $term = $this->ath_div($this->ath_sub($num, '1'), $this->ath_bcadd($num, '1')); // (num - 1) / (num + 1)
        $termSquared = $this->ath_mul($term, $term); // Calculate (term^2)

        // Using the series expansion for ln(1+x): ln(1+x) = 2 * (x - (x^3 / 3) + (x^5 / 5) - ...)
        $currentTerm = $term; // First term
        for ($n = 1; $n < 100; $n += 2) {
            $term_divided = $this->ath_div($currentTerm, (string)$n); // term / n
            $result = $this->ath_bcadd($result, $term_divided); // Add or subtract based on sign
            $currentTerm = $this->ath_mul($currentTerm, $termSquared); // Update current term for next iteration
        }

        return $this->ath_mul($result, '2'); // Multiply the result by 2
    }

    /**
     * Finds the maximum value from a set of values.
     *
     * This function compares a series of values and returns the maximum value.
     * It can handle an array of values or multiple arguments.
     *
     * @param string|array $value The first value or an array of values.
     * @param string ...$values Additional values to compare (optional).
     * @return string The maximum value.
     * @throws \InvalidArgumentException If the input array is empty.
     */
    protected function ath_max(mixed $value, mixed ...$values): string
    {
        // If only one argument is passed and it's an array, handle it directly
        if (func_num_args() === 1 && is_array($value)) {
            if (empty($value)) {
                throw new \InvalidArgumentException("Array cannot be empty.");
            }

            $maxValue = (string)$value[0];
            foreach ($value as $val) {
                if ($this->ath_comp((string)$val, $maxValue) > 0) {
                    $maxValue = (string)$val;
                }
            }

            return $maxValue;
        }

        // Compare multiple values
        $maxValue = (string)$value;
        foreach ($values as $val) {
            if ($this->ath_comp((string)$val, $maxValue) > 0) {
                $maxValue = (string)$val;
            }
        }

        return $maxValue;
    }

    /**
     * Finds the minimum value from a set of values.
     *
     * This function compares a series of values and returns the minimum value.
     * It can handle an array of values or multiple arguments.
     *
     * @param string|array $value The first value or an array of values.
     * @param string ...$values Additional values to compare (optional).
     * @return string The minimum value.
     * @throws \InvalidArgumentException If the input array is empty.
     */
    protected function ath_min(mixed $value, mixed ...$values): string
    {
        // If only one argument is passed and it's an array, handle it directly
        if (func_num_args() === 1 && is_array($value)) {
            if (empty($value)) {
                throw new \InvalidArgumentException("Array cannot be empty.");
            }

            $minValue = (string)$value[0];
            foreach ($value as $val) {
                if ($this->ath_comp((string)$val, $minValue) < 0) {
                    $minValue = (string)$val;
                }
            }

            return $minValue;
        }

        // Compare multiple values
        $minValue = (string)$value;
        foreach ($values as $val) {
            if ($this->ath_comp((string)$val, $minValue) < 0) {
                $minValue = (string)$val;
            }
        }

        return $minValue;
    }

    /**
     * Private method that calculates the modulus of two arbitrarily large numbers as strings.
     * Simulates the behavior of bcmod.
     */
    protected function ath_mod(string $num1, string $num2, ?int $scale = null): string
    {
        if ($num2 === '0' || $num2 === '0.0') {
            throw new \Exception('Division by zero');
        }

        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $quotient = $this->ath_div($num1, $num2, 0);
        $product = $this->multiplyStrings($quotient, $num2);
        $remainder = $this->subtractStrings($num1, $product);

        if ($scale !== null) {
            return $this->adjustScale($remainder, $scale);
        }

        return $remainder;
    }

    /**
     * Private method that multiplies two arbitrarily large numbers as strings.
     * Simulates the behavior of bcmul.
     */
    protected function ath_mul(string $num1, string $num2, ?int $scale = null): string
    {
        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $total_frac_length = strlen($num1_frac) + strlen($num2_frac);

        $num1_full = $num1_int . $num1_frac;
        $num2_full = $num2_int . $num2_frac;

        $product = $this->multiplyStrings($num1_full, $num2_full);
        $product = $this->insertDecimalPoint($product, $total_frac_length);

        if ($scale !== null) {
            return $this->adjustScale($product, $scale);
        }

        return $product;
    }

    /**
     * Converts an octal string to its decimal equivalent.
     *
     * This function takes a valid octal string (composed of digits 0-7) and converts it to its decimal equivalent.
     * It does not use native PHP functions and relies on BC Math for arbitrary precision.
     *
     * @param string $octal_string The octal string to convert.
     * @return string The decimal equivalent of the octal string (as a string).
     * @throws \InvalidArgumentException If the input is not a valid octal string.
     */
    protected function ath_octdec(string $octal_string): string
    {
        // Validate input to ensure it's a valid octal string
        if (!preg_match('/^[0-7]+$/', $octal_string)) {
            throw new \InvalidArgumentException("Input must be a valid octal string.");
        }

        $decimal_value = '0';  // Use string for arbitrary precision
        $length = strlen($octal_string);

        // Convert octal string to decimal
        for ($i = 0; $i < $length; $i++) {
            $digit = $octal_string[$length - $i - 1];  // Get the current digit
            $power_of_8 = $this->ath_pow('8', (string)$i);  // Calculate 8^i
            $decimal_value = $this->ath_bcadd($decimal_value, $this->ath_mul($digit, $power_of_8));  // Add digit * 8^i to the result
        }

        return $decimal_value;  // Return the result as a string
    }

    /**
     * Returns the value of π (pi) as a string for arbitrary precision calculations.
     *
     * This function returns a string approximation of π for use in BC Math operations.
     *
     * @return string The value of π (as a string).
     */
    protected function ath_pi(): string
    {
        // Return a constant value for pi
        return (string)A_PI;  // Approximation of π with high precision
    }


    /**
     * Raises a number to the power of the given exponent using BC Math.
     *
     * This function calculates the result of raising a number to the given exponent using the
     * algorithm of binary exponentiation for efficiency. It supports arbitrary precision and can
     * handle both small and large exponents.
     *
     * @param string $num The base number (as a string).
     * @param string $exponent The exponent (as a string).
     * @param int|null $scale The number of decimal places to use (optional).
     * @return string The result of raising $num to the power of $exponent (as a string).
     */
    protected function ath_pow(
        string $num,
        string $exponent,
        ?int $scale = null
    ): string {
        // Convert exponent to integer for binary exponentiation
        $exponent = (int) $exponent;

        // Special cases
        if (
            $exponent == 0
        ) {
            return '1'; // Any number raised to the power of 0 is 1
        }
        if ($exponent == 1) {
            return $num; // Any number raised to the power of 1 is the number itself
        }
        if ($num == '0') {
            return '0'; // 0 raised to any power is 0
        }

        // Initialize result as 1
        $result = '1';
        $base = $num;

        // Binary exponentiation algorithm
        while ($exponent > 0) {
            // If the exponent is odd, multiply the result by the base
            if ($exponent % 2 == 1) {
                $result = $this->multiplyStrings($result, $base);
            }
            // Square the base and divide the exponent by 2
            $base = $this->multiplyStrings($base, $base);
            $exponent = (int)($exponent / 2);
        }

        // Adjust the scale if specified
        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Private method that calculates modular exponentiation of a number.
     * Simulates the behavior of bcpowmod.
     */
    protected function ath_powmod(string $num, string $exponent, string $modulus, ?int $scale = null): string
    {
        if ($modulus === '0') {
            throw new \Exception('Division by zero in modulus');
        }

        $exponent = (int) $exponent;

        if ($exponent == 0) {
            return '1';
        }
        if ($num == '0') {
            return '0';
        }

        $result = '1';
        $base = $this->ath_mod($num, $modulus);

        while ($exponent > 0) {
            if ($exponent % 2 == 1) {
                $result = $this->ath_mod($this->multiplyStrings($result, $base), $modulus);
            }
            $base = $this->ath_mod($this->multiplyStrings($base, $base), $modulus);
            $exponent = (int)($exponent / 2);
        }

        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Converts an angle from radians to degrees.
     *
     * This function converts an angle in radians to degrees using BC Math for arbitrary precision.
     *
     * @param string $num The angle in radians (as a string).
     * @return string The angle in degrees (as a string).
     */
    protected function ath_rad2deg(string $num): string
    {
        // Approximation of π as a string
        $pi = $this->ath_pi();

        // Convert radians to degrees: degrees = radians * (180 / π)
        $factor = $this->ath_div('180', $pi);  // 180 / π
        return $this->ath_mul($num, $factor);  // radians * (180 / π)
    }

    /**
     * Rounds a number to a specified precision using BC Math.
     *
     * This function rounds a number to the specified precision (number of decimal places)
     * using various rounding modes. It handles both positive and negative precision values.
     *
     * @param string $num The number to round (as a string).
     * @param int $precision The number of decimal places to round to (default is 0).
     * @param int $mode The rounding mode (default is PHP_ROUND_HALF_UP).
     * @return string The rounded number (as a string).
     * @throws \InvalidArgumentException If an invalid rounding mode is provided.
     */
    protected function ath_round(string $num, int $precision = 0, int $mode = PHP_ROUND_HALF_UP): string
    {
        // Handle the case for negative precision
        if ($precision < 0) {
            // Calculate the factor 10^(-precision)
            $factor = $this->ath_pow('10', (string)abs($precision));
            // Perform rounding by dividing, rounding, and multiplying back
            $result = $this->ath_div($num, $factor);
            $rounded = $this->ath_round($result, 0, $mode); // Round to nearest integer
            return $this->ath_mul(
                $rounded,
                $factor
            );
        }

        // Calculate the factor 10^(precision) for rounding
        $factor = $this->ath_pow('10', (string)$precision);

        // Multiply the number by the factor to shift the decimal point
        $shifted_num = $this->ath_mul(
            $num,
            $factor
        );

        // Round based on the specified mode
        switch ($mode) {
            case A_ROUND_HALF_UP: // 1
                $rounded = $this->ath_bcadd($shifted_num, '0.5');
                $rounded = $this->ath_floor($rounded);
                break;
            case A_ROUND_HALF_DOWN: // 2
                $rounded = $this->ath_sub($shifted_num, '0.5');
                $rounded = $this->ath_floor($rounded);
                break;
            case A_ROUND_HALF_EVEN: // 3
                $rounded = $this->ath_bcadd($shifted_num, '0.5');
                $rounded = $this->ath_floor($rounded);
                if ($this->ath_mod($rounded, '2') !== '0') {
                    $rounded = $this->ath_sub($rounded, '1');
                }
                break;
            case A_ROUND_HALF_ODD: // 4
                $rounded = $this->ath_bcadd($shifted_num, '0.5');
                $rounded = $this->ath_floor($rounded);
                if ($this->ath_mod($rounded, '2') === '0') {
                    $rounded = $this->ath_bcadd($rounded, '1');
                }
                break;
            default:
                throw new \InvalidArgumentException("Invalid rounding mode.");
        }

        // Divide by the factor to shift the decimal point back
        return $this->ath_div(
            $rounded,
            $factor
        );
    }

    /**
     * Calculates the sine of a number (in radians) using the Taylor series expansion.
     *
     * This function computes the sine of a number using the Taylor series expansion for sine.
     * The input number is normalized to the range [0, 2π] to improve precision.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num The angle in radians (as a string).
     * @return string The sine of the given angle (as a string).
     */
    protected function ath_sin(string $num): string
    {
        // Normalize the angle to the range [0, 2π]
        $two_pi = $this->ath_mul('2', (string)A_PI); // 2π
        $num = $this->ath_mod($num, $two_pi); // num mod 2π

        // Initialize the result and the first term of the Taylor series
        $result = '0'; // Initialize the result
        $term = $num;  // First term is x
        $n = 1;        // Start with the first term in the series

        // Use 10 terms for better precision
        while ($n <= 10) {
            // Add the current term to the result
            $result = $this->ath_bcadd($result, $term);

            // Calculate the next term: (-1)^n * (x^(2n+1)) / (2n+1)!
            $term = $this->ath_mul($term, $this->ath_mul($this->ath_sub('0', $num), $num)); // Multiply by -x^2
            $term = $this->ath_div($term, $this->ath_mul((string)(2 * $n), (string)(2 * $n + 1))); // Divide by (2n+1)!

            $n++; // Increment to the next term
        }

        return $result; // Return the result as a string
    }

    /**
     * Calculates the hyperbolic sine of a number (sinh) using the formula sinh(x) = (e^x - e^-x) / 2.
     *
     * This function computes the hyperbolic sine of a number using the exponential function for e^x and e^-x.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @return string The hyperbolic sine of the given number (as a string).
     */
    protected function ath_sinh(string $num): string
    {
        // Calculate e^x
        $exp_x = $this->ath_exp($num);

        // Calculate e^(-x) which is 1 / e^x
        $exp_neg_x = $this->ath_div('1', $exp_x);

        // Subtract e^-x from e^x and divide by 2 to calculate sinh(x)
        $difference = $this->ath_sub($exp_x, $exp_neg_x);

        // Divide the result by 2
        return $this->ath_div($difference, '2');
    }

    /**
     * Calculates the square root of a non-negative number using the Babylonian (Newton's) method.
     *
     * This function computes the square root of a non-negative number using an iterative approach.
     * It does not use native PHP functions for the calculation.
     *
     * @param string $num The number to evaluate (as a string).
     * @param int|null $scale The number of decimal places to consider in the approximation (optional).
     * @return string The square root of the given number (as a string).
     * @throws \InvalidArgumentException If the input is negative.
     */
    protected function ath_sqrt(string $num, ?int $scale = null): string
    {
        // Validate input to ensure it's a non-negative number
        if ($this->ath_comp($num, '0') < 0) {
            throw new \InvalidArgumentException("Input must be a non-negative number.");
        }

        // Special case for 0
        if ($this->ath_comp($num, '0') === 0) {
            return '0';
        }

        // Initialize the scale if not provided
        if ($scale === null) {
            $scale = 10; // Default scale to 10 decimal places
        }

        // Using the Babylonian method (Newton's method) for square root approximation
        $guess = $this->ath_div($num, '2', $scale); // Initial guess
        $epsilon = str_pad('1', $scale + 1, '0', STR_PAD_RIGHT); // Tolerance level based on the scale

        // Iteratively improve the guess
        while ($this->ath_comp($this->ath_abs($this->ath_sub($this->ath_mul($guess, $guess), $num)), $epsilon) > 0) {
            $guess = $this->ath_div($this->ath_bcadd($guess, $this->ath_div($num, $guess, $scale)), '2', $scale); // Update guess
        }

        return $guess;
    }

    /**
     * Private method that subtracts two arbitrarily large numbers as strings.
     * Simulates the behavior of bcsub.
     */
    protected function ath_sub(string $num1, string $num2, ?int $scale = null): string
    {
        if ($scale === null) {
            $scale = $this->globalScale;
        }

        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $max_frac_length = max(strlen($num1_frac), strlen($num2_frac));
        $num1_frac = str_pad($num1_frac, $max_frac_length, '0', STR_PAD_RIGHT);
        $num2_frac = str_pad($num2_frac, $max_frac_length, '0', STR_PAD_RIGHT);

        $num1_full = $num1_int . $num1_frac;
        $num2_full = $num2_int . $num2_frac;

        $result = $this->subtractStrings($num1_full, $num2_full);
        $result = $this->insertDecimalPoint($result, $max_frac_length);

        return $this->adjustScale($result, $scale);
    }

    /**
     * Private method to set or get the global scale used in all operations.
     * Simulates the behavior of bcscale.
     */
    protected function ath_scale(?int $scale = null): int
    {
        if ($scale === null) {
            return $this->globalScale;
        }

        $this->globalScale = $scale;
        return $this->globalScale;
    }

    /**
     * Calculates the tangent of a number (angle in radians) using BC Math functions.
     *
     * This function computes the tangent of an angle in radians using the formula:
     * tan(x) = sin(x) / cos(x). It does not use native PHP functions for the calculation.
     *
     * @param string $num The angle in radians (as a string).
     * @return string The tangent of the given angle (as a string).
     * @throws \InvalidArgumentException If the tangent is undefined due to division by zero.
     */
    protected function ath_tan(string $num): string
    {
        // Normalize the angle to the range [0, 2π]
        $two_pi = $this->ath_mul('2', (string)A_PI); // 2π
        $num = $this->ath_mod($num, $two_pi); // num mod 2π

        // Calculate sine and cosine using previously defined BC Math functions
        $sine = $this->ath_sin($num);
        $cosine = $this->ath_cos($num);

        // Check for cosine equal to zero to avoid division by zero
        if ($this->ath_comp($this->ath_abs($cosine), '1e-12') < 0) { // Using a small tolerance
            throw new \InvalidArgumentException("Tangent is undefined for this angle.");
        }

        // Return the tangent as sin(x) / cos(x)
        return $this->ath_div($sine, $cosine);
    }

    /**
     * Calculates the hyperbolic tangent of a number using BC Math functions.
     *
     * This function computes the hyperbolic tangent of a number using the formula:
     * tanh(x) = sinh(x) / cosh(x). It does not use native PHP functions for the calculation.
     *
     * @param string $num The input number (as a string).
     * @return string The hyperbolic tangent of the given number (as a string).
     * @throws \InvalidArgumentException If the hyperbolic tangent is undefined due to division by zero.
     */
    protected function ath_tanh(string $num): string
    {
        // Calculate hyperbolic sine and hyperbolic cosine using previously defined BC Math functions
        $sinh = $this->ath_sinh($num);
        $cosh = $this->ath_cosh($num);

        // Check for cosh equal to zero to avoid division by zero
        if ($this->ath_comp($this->ath_abs($cosh), '1e-12') < 0) { // Using a small tolerance
            throw new \InvalidArgumentException("Tanh is undefined for this value.");
        }

        // Return the hyperbolic tangent as sinh(x) / cosh(x)
        return $this->ath_div($sinh, $cosh);
    }


    /**
     * -------------------
     * * Private Methods *
     * -------------------
     */

    /**
     * Helper function that adds two large numbers represented as strings.
     */
    private function addStrings(string $num1, string $num2): string
    {
        $maxLength = max(strlen($num1), strlen($num2));
        $num1 = str_pad($num1, $maxLength, '0', STR_PAD_LEFT);
        $num2 = str_pad($num2, $maxLength, '0', STR_PAD_LEFT);

        $carry = 0;
        $result = '';

        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $sum = (int)$num1[$i] + (int)$num2[$i] + $carry;
            $carry = (int)($sum / 10);
            $result = ($sum % 10) . $result;
        }

        if ($carry > 0) {
            $result = $carry . $result;
        }

        return $result;
    }

    /**
     * Adjusts the scale (decimal places) of a number to the desired decimal places.
     * Truncates or pads the number with zeros as necessary.
     *
     * @param string $number The number as a string
     * @param int $scale The number of decimal places to adjust to
     * @return string The adjusted number with the specified scale
     */
    protected function adjustScale(string $number, int $scale): string
    {
        if ($scale === 0) {
            return strstr($number, '.', true) ?: $number;
        }

        list($integer, $fractional) = $this->splitNumber($number);

        if (strlen($fractional) > $scale) {
            $fractional = substr($fractional, 0, $scale);
        } else {
            $fractional = str_pad($fractional, $scale, '0', STR_PAD_RIGHT);
        }

        return $integer . '.' . $fractional;
    }

    /**
     * Helper function to compare two large numbers represented as strings.
     * Returns 0 if equal, 1 if num1 > num2, -1 if num1 < num2.
     */
    private function compareStrings(string $num1, string $num2): int
    {
        $num1 = ltrim($num1, '0');
        $num2 = ltrim($num2, '0');

        if (strlen($num1) > strlen($num2)) {
            return 1;
        } elseif (strlen($num1) < strlen($num2)) {
            return -1;
        }

        for ($i = 0; $i < strlen($num1); $i++) {
            if ($num1[$i] > $num2[$i]) {
                return 1;
            } elseif ($num1[$i] < $num2[$i]) {
                return -1;
            }
        }

        return 0;
    }

    /**
     * Helper function to divide two large numbers represented as strings.
     */
    private function divideStrings(string $num1, string $num2, ?int $scale = null): string
    {
        if ($num2 === '0') {
            throw new \Exception('Division by zero');
        }

        if ($scale === null) {
            $scale = $this->globalScale;
        }

        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        $num1_full = $num1_int . $num1_frac;
        $num2_full = $num2_int . $num2_frac;

        $scale_factor = strlen($num1_frac) - strlen($num2_frac);

        if ($scale_factor > 0) {
            $num2_full = str_pad($num2_full, strlen($num2_full) + $scale_factor, '0');
        } elseif ($scale_factor < 0) {
            $num1_full = str_pad($num1_full, strlen($num1_full) - $scale_factor, '0');
        }

        $quotient = $this->longDivision($num1_full, $num2_full);
        $quotient = $this->insertDecimalPoint($quotient, $scale);

        return $this->adjustScale($quotient, $scale);
    }

    /**
     * Helper function to get an initial approximation of the square root.
     */
    private function initialApproximation(string $num): string
    {
        $length = strlen($num);
        $approx = '1' . str_repeat('0', (int)(($length - 1) / 2));
        return $approx;
    }

    /**
     * Helper function to insert a decimal point in a number based on the scale.
     */
    private function insertDecimalPoint(string $number, int $scale): string
    {
        $int_part = substr($number, 0, strlen($number) - $scale);
        $frac_part = substr($number, -$scale);

        if ($int_part === '') {
            $int_part = '0';
        }

        return $int_part . '.' . $frac_part;
    }

    /**
     * Helper function that performs long division on two large numbers represented as strings.
     */
    private function longDivision(string $num1, string $num2): string
    {
        $result = '';
        $current = '0';

        for ($i = 0; $i < strlen($num1); $i++) {
            $current .= $num1[$i];
            $current = ltrim($current, '0');

            $div = 0;
            while ($this->compareStrings($current, $num2) >= 0) {
                $current = $this->subtractStrings($current, $num2);
                $div++;
            }

            $result .= (string)$div;
        }

        return $result === '' ? '0' : ltrim($result, '0');
    }

    /**
     * Helper function that multiplies two large numbers represented as strings.
     */
    private function multiplyStrings(string $num1, string $num2): string
    {
        $num1 = ltrim($num1, '0');
        $num2 = ltrim($num2, '0');

        $result = array_fill(0, strlen($num1) + strlen($num2), 0);

        for ($i = strlen($num1) - 1; $i >= 0; $i--) {
            for ($j = strlen($num2) - 1; $j >= 0; $j--) {
                $product = (int)$num1[$i] * (int)$num2[$j];
                $position = $i + $j + 1;

                $sum = $result[$position] + $product;
                $result[$position] = $sum % 10;
                $result[$position - 1] += intdiv($sum, 10);
            }
        }

        $result = implode('', $result);
        return ltrim($result, '0') ?: '0';
    }

    /**
     * Helper function that subtracts two large numbers represented as strings.
     */
    private function subtractStrings(string $num1, string $num2): string
    {
        $maxLength = max(strlen($num1), strlen($num2));
        $num1 = str_pad($num1, $maxLength, '0', STR_PAD_LEFT);
        $num2 = str_pad($num2, $maxLength, '0', STR_PAD_LEFT);

        $carry = 0;
        $result = '';

        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $diff = (int)$num1[$i] - (int)$num2[$i] - $carry;
            if ($diff < 0) {
                $diff += 10;
                $carry = 1;
            } else {
                $carry = 0;
            }
            $result = $diff . $result;
        }

        return ltrim($result, '0') ?: '0';
    }

    /**
     * Splits a number into integer and fractional parts.
     *
     * @param string $number The number as a string
     * @return array An array containing two elements: [integer part, fractional part]
     */
    protected function splitNumber(string $number): array
    {
        $parts = explode('.', $number);
        return [
            $parts[0],
            isset($parts[1]) ? $parts[1] : ''
        ];
    }

    /**
     * Helper function to calculate the natural logarithm using the Taylor series.
     *
     * @param string $x The input value (as a string)
     * @param int $precision The number of terms to use in the Taylor series expansion
     * @return string The logarithm approximation as a string
     */
    private function taylorLogarithm(string $x, int $precision): string
    {
        // Taylor series for ln(x) around x = 1: ln(x) = (x - 1) - (x - 1)^2/2 + (x - 1)^3/3 - ...
        $x_minus_1 = bcsub($x, '1');  // x - 1
        $logApprox = $x_minus_1;
        $term = $x_minus_1;
        $sign = -1;

        for ($n = 2; $n <= $precision; $n++) {
            $term = $this->ath_mul($term, $x_minus_1);  // (x - 1)^n
            $termDivided = $this->ath_div($term, (string)$n);  // (x - 1)^n / n
            if ($sign === -1) {
                $logApprox = $this->ath_sub($logApprox, $termDivided);
            } else {
                $logApprox = $this->ath_bcadd($logApprox, $termDivided);
            }
            $sign *= -1;
        }

        return $logApprox;
    }
}
