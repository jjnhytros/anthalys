<?php

declare(strict_types=1);

namespace App\Services\ATHMath;

class AddATHMath extends ATHMath
{
    /**
     * Adds two numbers using basic arithmetic rules (integer or decimal).
     * This function assumes the numbers are provided as strings and adds them without special handling for scale.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @return string The result of the addition as a string
     */
    public function addStandard(string $num1, string $num2): string
    {
        // Convert string inputs to floats (or integers) for basic arithmetic operations
        $result = (float)$num1 + (float)$num2;

        // Return the result converted back to string to maintain consistency
        return (string)$result;
    }

    /**
     * Adds two numbers using arithmetic with carry (portata).
     * This function simulates how addition with carry works by managing the carry-over between columns.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the addition with carry as a string
     */
    public function addWithCarry(string $num1, string $num2, ?int $scale = null): string
    {
        // Use the existing addStrings method from ATHMath which already handles columnar addition with carry
        $result = $this->addStringsWithCarry($num1, $num2);

        // If a scale is provided, adjust the scale of the result
        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Adds two numbers column by column, handling carry for each column.
     * This is a digit-by-digit addition starting from the rightmost digit (units) and moving left.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the column addition as a string
     */
    public function addColumn(string $num1, string $num2, ?int $scale = null): string
    {
        // Normalize the numbers to the same length, including the fractional parts
        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        // Pad the fractional parts to the same length
        $max_frac_length = max(strlen($num1_frac), strlen($num2_frac));
        $num1_frac = str_pad($num1_frac, $max_frac_length, '0', STR_PAD_RIGHT);
        $num2_frac = str_pad($num2_frac, $max_frac_length, '0', STR_PAD_RIGHT);

        // Add the fractional parts first
        $frac_sum = $this->addStringsWithCarry($num1_frac, $num2_frac);

        // If there's a carry from the fractional part, it should be added to the integer part
        $carry = 0;
        if (strlen($frac_sum) > $max_frac_length) {
            $carry = substr($frac_sum, 0, 1); // Extract carry
            $frac_sum = substr($frac_sum, 1); // Remove carry from fractional part
        }

        // Now pad the integer parts to the same length
        $max_int_length = max(strlen($num1_int), strlen($num2_int));
        $num1_int = str_pad($num1_int, $max_int_length, '0', STR_PAD_LEFT);
        $num2_int = str_pad($num2_int, $max_int_length, '0', STR_PAD_LEFT);

        // Add the integer parts
        $int_sum = $this->addStringsWithCarry($num1_int, $num2_int);

        // If there's a carry from the fractional part, add it to the integer sum
        if ($carry > 0) {
            $int_sum = $this->addStringsWithCarry($int_sum, (string)$carry);
        }

        // Combine the integer and fractional parts
        $result = $int_sum;
        if ($max_frac_length > 0) {
            $result .= '.' . $frac_sum;
        }

        // Adjust the scale if provided
        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Adds two numbers and returns the result modulo n.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @param string $mod The modulus n as a string
     * @return string The result of (num1 + num2) % mod as a string
     * @throws \Exception If the modulus is zero
     */
    public function addModulo(string $num1, string $num2, string $mod): string
    {
        // Check if modulus is zero, which would be invalid
        if ($mod === '0') {
            throw new \Exception('Modulus cannot be zero');
        }

        // Step 1: Add the two numbers using the existing add method from ATHMath
        $sum = $this->add($num1, $num2);

        // Step 2: Calculate the result modulo n
        return $this->mod($sum, $mod);
    }

    /**
     * Adds two binary numbers represented as strings.
     *
     * @param string $bin1 The first binary number as a string
     * @param string $bin2 The second binary number as a string
     * @return string The result of the binary addition as a string
     */
    public function addBinary(string $bin1, string $bin2): string
    {
        // Pad the shorter binary string with leading zeros to make both strings the same length
        $maxLength = max(strlen($bin1), strlen($bin2));
        $bin1 = str_pad($bin1, $maxLength, '0', STR_PAD_LEFT);
        $bin2 = str_pad($bin2, $maxLength, '0', STR_PAD_LEFT);

        $carry = 0;
        $result = '';

        // Perform binary addition from right to left
        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $bit1 = (int) $bin1[$i];
            $bit2 = (int) $bin2[$i];

            // Binary addition with carry
            $sum = $bit1 + $bit2 + $carry;
            if ($sum >= 2) {
                $carry = 1; // Carry for the next column
                $result = (string)($sum - 2) . $result; // Subtract 2 and append the result
            } else {
                $carry = 0; // No carry
                $result = (string)$sum . $result;
            }
        }

        // If there's a final carry, prepend it to the result
        if ($carry > 0) {
            $result = '1' . $result;
        }

        return $result;
    }

    /**
     * Adds two decimal numbers represented as strings.
     *
     * @param string $num1 The first decimal number as a string
     * @param string $num2 The second decimal number as a string
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the decimal addition as a string
     */
    public function addDecimal(string $num1, string $num2, ?int $scale = null): string
    {
        // Split both numbers into integer and fractional parts
        list($num1_int, $num1_frac) = $this->splitNumber($num1);
        list($num2_int, $num2_frac) = $this->splitNumber($num2);

        // Pad the fractional parts to the same length
        $max_frac_length = max(strlen($num1_frac), strlen($num2_frac));
        $num1_frac = str_pad($num1_frac, $max_frac_length, '0', STR_PAD_RIGHT);
        $num2_frac = str_pad($num2_frac, $max_frac_length, '0', STR_PAD_RIGHT);

        // Add the fractional parts first
        $frac_sum = $this->addStringsWithCarry($num1_frac, $num2_frac);

        // Handle carry from fractional part
        $carry = 0;
        if (strlen($frac_sum) > $max_frac_length) {
            $carry = substr($frac_sum, 0, 1); // Extract carry
            $frac_sum = substr($frac_sum, 1); // Remove carry from fractional part
        }

        // Now pad the integer parts to the same length
        $max_int_length = max(strlen($num1_int), strlen($num2_int));
        $num1_int = str_pad($num1_int, $max_int_length, '0', STR_PAD_LEFT);
        $num2_int = str_pad($num2_int, $max_int_length, '0', STR_PAD_LEFT);

        // Add the integer parts and include the carry
        $int_sum = $this->addStringsWithCarry($num1_int, $num2_int);
        if ($carry > 0) {
            $int_sum = $this->addStringsWithCarry($int_sum, (string)$carry);
        }

        // Combine integer and fractional parts
        $result = $int_sum . '.' . $frac_sum;

        // Adjust scale if provided
        if ($scale !== null) {
            return $this->adjustScale($result, $scale);
        }

        return $result;
    }

    /**
     * Adds two rational numbers represented by their numerators and denominators.
     *
     * @param string $num1_numerator The numerator of the first rational number
     * @param string $num1_denominator The denominator of the first rational number
     * @param string $num2_numerator The numerator of the second rational number
     * @param string $num2_denominator The denominator of the second rational number
     * @return string The result of the addition as a rational number in the form "numerator/denominator"
     */
    public function addRational(string $num1_numerator, string $num1_denominator, string $num2_numerator, string $num2_denominator): string
    {
        // Multiply the numerators by the denominators of the other number to get a common denominator
        $new_numerator1 = $this->multiply($num1_numerator, $num2_denominator);
        $new_numerator2 = $this->multiply($num2_numerator, $num1_denominator);
        $common_denominator = $this->multiply($num1_denominator, $num2_denominator);

        // Add the new numerators together
        $result_numerator = $this->add($new_numerator1, $new_numerator2);

        // Simplify the fraction by dividing both the numerator and denominator by their GCD
        $gcd = $this->gcd($result_numerator, $common_denominator);
        $simplified_numerator = $this->divide($result_numerator, $gcd);
        $simplified_denominator = $this->divide($common_denominator, $gcd);

        // Return the result in the form "numerator/denominator"
        return $simplified_numerator . '/' . $simplified_denominator;
    }

    /**
     * Adds two irrational numbers represented as approximations of strings.
     * The function takes the approximations of the irrational numbers (like pi, sqrt(2)) as input.
     *
     * @param string $irrational1 The first irrational number (approximated as a string)
     * @param string $irrational2 The second irrational number (approximated as a string)
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the addition as an approximation
     */
    public function addIrrational(string $irrational1, string $irrational2, ?int $scale = null): string
    {
        // Add the two numbers using the existing 'add' function from ATHMath
        $sum = $this->add($irrational1, $irrational2);

        // Adjust the scale (precision) of the result if a scale is provided
        if ($scale !== null) {
            return $this->adjustScale($sum, $scale);
        }

        return $sum;
    }

    /**
     * Adds two numbers represented in base n using custom math functions (ath_ methods).
     *
     * @param string $num1 The first number in base n
     * @param string $num2 The second number in base n
     * @param int $base The base in which the numbers are represented
     * @return string The result of the addition in base n
     * @throws \Exception If the base is less than 2
     */
    public function addBaseN(string $num1, string $num2, int $base): string
    {
        // Check if the base is valid (minimum base is 2)
        if ($base < 2) {
            throw new \Exception('Base must be greater than or equal to 2');
        }

        // Step 1: Convert num1 and num2 from base n to base 10 using custom methods
        $num1_decimal = $this->baseToDecimal($num1, $base);
        $num2_decimal = $this->baseToDecimal($num2, $base);

        // Step 2: Add the numbers in base 10 using the custom 'ath_bcadd' method
        $sum_decimal = $this->add($num1_decimal, $num2_decimal);

        // Step 3: Convert the sum from base 10 back to base n using custom methods
        return $this->decimalToBase($sum_decimal, $base);
    }

    /**
     * Iteratively sums a list of numbers.
     *
     * @param array $numbers The array of numbers to sum (each number is a string)
     * @param ?int $scale Optional number of decimal places for the result
     * @return string The result of the iterative addition
     */
    public function addIterative(array $numbers, ?int $scale = null): string
    {
        // Initialize the total sum as 0
        $total_sum = '0';

        // Iterate over the list of numbers
        foreach ($numbers as $number) {
            // Add each number to the total sum using the custom ath_bcadd method
            $total_sum = $this->ath_bcadd($total_sum, $number);
        }

        // If a scale is provided, adjust the result to the desired precision
        if ($scale !== null) {
            return $this->adjustScale($total_sum, $scale);
        }

        return $total_sum;
    }

    /**
     * Adds two fractions represented by their numerators and denominators.
     *
     * @param string $num1_numerator The numerator of the first fraction
     * @param string $num1_denominator The denominator of the first fraction
     * @param string $num2_numerator The numerator of the second fraction
     * @param string $num2_denominator The denominator of the second fraction
     * @return string The result of the fraction addition in the form "numerator/denominator"
     */
    public function addFractions(
        string $num1_numerator,
        string $num1_denominator,
        string $num2_numerator,
        string $num2_denominator
    ): string {
        // Multiply the numerators by the denominators of the other fraction
        $new_numerator1 = $this->ath_bcmul($num1_numerator, $num2_denominator);
        $new_numerator2 = $this->ath_bcmul($num2_numerator, $num1_denominator);

        // Calculate the common denominator
        $common_denominator = $this->ath_bcmul($num1_denominator, $num2_denominator);

        // Add the numerators
        $result_numerator = $this->ath_bcadd($new_numerator1, $new_numerator2);

        // Simplify the fraction by dividing both the numerator and denominator by their GCD
        $gcd = $this->gcd($result_numerator, $common_denominator);
        $simplified_numerator = $this->ath_bcdiv($result_numerator, $gcd);
        $simplified_denominator = $this->ath_bcdiv($common_denominator, $gcd);

        // Return the result as "numerator/denominator"
        return $simplified_numerator . '/' . $simplified_denominator;
    }

    /**
     * Adds two angular measurements with fractions (degrees, minutes, seconds).
     *
     * @param string $deg1 Numerator of the first fraction representing degrees
     * @param string $min1 Numerator of the first fraction representing minutes
     * @param string $sec1 Numerator of the first fraction representing seconds
     * @param string $deg2 Numerator of the second fraction representing degrees
     * @param string $min2 Numerator of the second fraction representing minutes
     * @param string $sec2 Numerator of the second fraction representing seconds
     * @return string The result of the angle addition in the form "degrees° minutes′ seconds″"
     */
    public function addAngularFractions(
        string $deg1,
        string $min1,
        string $sec1,
        string $deg2,
        string $min2,
        string $sec2
    ): string {
        // Somma dei secondi
        $total_seconds = $this->ath_bcadd($sec1, $sec2);

        // Gestione del riporto per i secondi (60 secondi = 1 minuto)
        $carry_minutes = '0';
        if ($this->ath_bccomp($total_seconds, '60') >= 0) {
            $carry_minutes = $this->ath_bcdiv($total_seconds, '60', 0);  // Riporto per i minuti
            $total_seconds = $this->ath_bcmod($total_seconds, '60');      // Secondi rimanenti
        }

        // Somma dei minuti
        $total_minutes = $this->ath_bcadd($this->ath_bcadd($min1, $min2), $carry_minutes);

        // Gestione del riporto per i minuti (60 minuti = 1 grado)
        $carry_degrees = '0';
        if ($this->ath_bccomp($total_minutes, '60') >= 0) {
            $carry_degrees = $this->ath_bcdiv($total_minutes, '60', 0);  // Riporto per i gradi
            $total_minutes = $this->ath_bcmod($total_minutes, '60');      // Minuti rimanenti
        }

        // Somma dei gradi
        $total_degrees = $this->ath_bcadd($this->ath_bcadd($deg1, $deg2), $carry_degrees);

        // Restituisci il risultato come "gradi° minuti′ secondi″"
        return $total_degrees . '° ' . $total_minutes . '′ ' . $total_seconds . '″';
    }

    /**
     * Adds two numbers which can include negative numbers, respecting arithmetic rules.
     *
     * @param string $num1 The first number (can be negative)
     * @param string $num2 The second number (can be negative)
     * @return string The result of the addition, considering the signs
     */
    public function addWithNegatives(string $num1, string $num2): string
    {
        // Check if num1 and/or num2 are negative
        $num1_negative = $this->isNegative($num1);
        $num2_negative = $this->isNegative($num2);

        // Remove the signs for internal calculation
        $abs_num1 = ltrim($num1, '-');
        $abs_num2 = ltrim($num2, '-');

        // Case 1: Both numbers are positive
        if (!$num1_negative && !$num2_negative) {
            return $this->ath_bcadd($abs_num1, $abs_num2);
        }

        // Case 2: Both numbers are negative
        if ($num1_negative && $num2_negative) {
            $sum = $this->ath_bcadd($abs_num1, $abs_num2);
            return '-' . $sum;  // The result is negative
        }

        // Case 3: One number is negative, the other is positive
        if ($num1_negative && !$num2_negative) {
            return $this->subtractWithSign($abs_num2, $abs_num1);
        }

        if (!$num1_negative && $num2_negative) {
            return $this->subtractWithSign($abs_num1, $abs_num2);
        }

        // Default return (shouldn't reach this)
        return '0';
    }

    /**
     * Adds two prime numbers, ensuring that both inputs are prime.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @return string The result of the addition if both numbers are prime
     * @throws \Exception If one of the numbers is not prime
     */
    public function addPrimes(string $num1, string $num2): string
    {
        // Verifica se num1 è primo
        if (!$this->isPrime($num1)) {
            throw new \Exception("The number $num1 is not a prime number.");
        }

        // Verifica se num2 è primo
        if (!$this->isPrime($num2)) {
            throw new \Exception("The number $num2 is not a prime number.");
        }

        // Se entrambi i numeri sono primi, esegui l'addizione
        return $this->ath_bcadd($num1, $num2);
    }

    /**
     * Adds two vectors component-wise.
     *
     * @param array $vector1 The first vector as an array of numbers (string format for each element)
     * @param array $vector2 The second vector as an array of numbers (string format for each element)
     * @return array The result of the vector addition as an array of numbers (in string format)
     * @throws \Exception If the vectors do not have the same length
     */
    public function addVectors(array $vector1, array $vector2): array
    {
        // Check if the two vectors have the same length
        if (count($vector1) !== count($vector2)) {
            throw new \Exception('Vectors must have the same length');
        }

        // Initialize an empty array for the result
        $result = [];

        // Perform component-wise addition
        for ($i = 0; $i < count($vector1); $i++) {
            // Add corresponding components using ath_bcadd
            $result[] = $this->ath_bcadd($vector1[$i], $vector2[$i]);
        }

        return $result;
    }

    /**
     * Adds two matrices element-wise.
     *
     * @param array $matrix1 The first matrix (2D array of numbers in string format)
     * @param array $matrix2 The second matrix (2D array of numbers in string format)
     * @return array The result of the matrix addition as a 2D array of numbers (in string format)
     * @throws \Exception If the matrices do not have the same dimensions
     */
    public function addMatrices(array $matrix1, array $matrix2): array
    {
        // Check if the two matrices have the same dimensions
        $rows1 = count($matrix1);
        $cols1 = count($matrix1[0]);
        $rows2 = count($matrix2);
        $cols2 = count($matrix2[0]);

        if ($rows1 !== $rows2 || $cols1 !== $cols2) {
            throw new \Exception('Matrices must have the same dimensions');
        }

        // Initialize the result matrix
        $result = [];

        // Perform element-wise addition
        for ($i = 0; $i < $rows1; $i++) {
            $row = [];
            for ($j = 0; $j < $cols1; $j++) {
                // Add corresponding elements using ath_bcadd
                $row[] = $this->ath_bcadd($matrix1[$i][$j], $matrix2[$i][$j]);
            }
            $result[] = $row;
        }

        return $result;
    }

    /**
     * Adds two tensors element-wise. Tensors can be of any order (dimensions).
     *
     * @param array|string $tensor1 The first tensor (multi-dimensional array or string for scalar values)
     * @param array|string $tensor2 The second tensor (multi-dimensional array or string for scalar values)
     * @return array|string The result of the tensor addition as a multi-dimensional array or scalar string
     * @throws \Exception If the tensors do not have the same dimensions
     */
    public function addTensors(array|string $tensor1, array|string $tensor2): array|string
    {
        // Check if the two tensors have the same structure (dimensions)
        if (!$this->checkDimensions($tensor1, $tensor2)) {
            throw new \Exception('Tensors must have the same dimensions');
        }

        // Perform the addition recursively
        return $this->addTensorsRecursively($tensor1, $tensor2);
    }

    /**
     * Adds a matrix to its transpose (or another matrix's transpose).
     *
     * @param array $matrix1 The first matrix (2D array of numbers in string format)
     * @param array|null $matrix2 The second matrix (optional, if provided, its transpose will be used)
     * @return array The result of the addition of the matrix and its transpose
     * @throws \Exception If the matrix is not square and $matrix2 is null (only square matrices can be added to their transpose)
     */
    public function addWithTranspose(array $matrix1, ?array $matrix2 = null): array
    {
        // If matrix2 is provided, use its transpose, otherwise use the transpose of matrix1
        $transpose = $matrix2 ? $this->transpose($matrix2) : $this->transpose($matrix1);

        // Ensure that the matrices are of the same dimensions
        if (!$this->checkSameDimensions($matrix1, $transpose)) {
            throw new \Exception('Matrices must have the same dimensions for addition');
        }

        // Perform the element-wise addition of the matrix and its transpose
        return $this->addMatrices($matrix1, $transpose);
    }

    /**
     * Performs the Kronecker product of two matrices and adds the result to another matrix.
     *
     * @param array $matrixA The first matrix (for the Kronecker product)
     * @param array $matrixB The second matrix (for the Kronecker product)
     * @param array|null $matrixC An optional matrix to add to the result (must be the same dimensions as the Kronecker product)
     * @return array The result of the Kronecker product added to the optional matrix
     * @throws \Exception If matrixC does not have the same dimensions as the Kronecker product result
     */
    public function addWithKroneckerProduct(array $matrixA, array $matrixB, ?array $matrixC = null): array
    {
        // Step 1: Calculate the Kronecker product of matrixA and matrixB
        $kroneckerProduct = $this->kroneckerProduct($matrixA, $matrixB);

        // Step 2: If matrixC is provided, check dimensions and perform element-wise addition
        if ($matrixC !== null) {
            if (!$this->checkSameDimensions($kroneckerProduct, $matrixC)) {
                throw new \Exception('Matrix C must have the same dimensions as the Kronecker product result');
            }

            // Perform element-wise addition
            return $this->addMatrices($kroneckerProduct, $matrixC);
        }

        // Return the Kronecker product if no matrixC is provided
        return $kroneckerProduct;
    }

    /**
     * Adds two sparse matrices represented as arrays of triplets (row, column, value).
     *
     * @param array $sparseMatrix1 The first sparse matrix (array of triplets)
     * @param array $sparseMatrix2 The second sparse matrix (array of triplets)
     * @return array The result of the sparse matrix addition, in sparse format
     */
    public function addSparseMatrices(array $sparseMatrix1, array $sparseMatrix2): array
    {
        // Use a dictionary (hash map) to store the sum of values by their positions (row, column)
        $resultMap = [];

        // Process the first matrix
        foreach ($sparseMatrix1 as $element) {
            list($row, $col, $value) = $element;
            $key = $row . ',' . $col;  // Create a unique key for the position (row, column)
            $resultMap[$key] = $value;
        }

        // Process the second matrix
        foreach ($sparseMatrix2 as $element) {
            list($row, $col, $value) = $element;
            $key = $row . ',' . $col;  // Create a unique key for the position (row, column)

            // If the position already exists, add the values
            if (isset($resultMap[$key])) {
                $resultMap[$key] = $this->ath_bcadd($resultMap[$key], $value);
            } else {
                $resultMap[$key] = $value;
            }
        }

        // Convert the result map back to sparse matrix format (array of triplets)
        $resultSparseMatrix = [];
        foreach ($resultMap as $key => $value) {
            if ($value !== '0') {  // We only store non-zero values
                list($row, $col) = explode(',', $key);
                $resultSparseMatrix[] = [(int)$row, (int)$col, $value];
            }
        }

        return $resultSparseMatrix;
    }

    /**
     * Adds two orthogonal matrices component-wise.
     *
     * @param array $matrix1 The first matrix (2D array of numbers in string format)
     * @param array $matrix2 The second matrix (2D array of numbers in string format)
     * @return array The result of the matrix addition
     * @throws \Exception If one or both matrices are not orthogonal
     */
    public function addOrthogonalMatrices(array $matrix1, array $matrix2): array
    {
        // Check if both matrices are orthogonal
        if (!$this->isOrthogonal($matrix1)) {
            throw new \Exception('Matrix 1 is not orthogonal');
        }
        if (!$this->isOrthogonal($matrix2)) {
            throw new \Exception('Matrix 2 is not orthogonal');
        }

        // Perform the element-wise addition of the matrices
        return $this->addMatrices($matrix1, $matrix2);
    }

    /**
     * Adds two tangent vectors in the tangent space at a given point on a differentiable manifold.
     *
     * @param array $vector1 The first tangent vector (represented as an array of components)
     * @param array $vector2 The second tangent vector (represented as an array of components)
     * @param array $point The point on the manifold where the tangent vectors are located
     * @return array The sum of the two tangent vectors as an array of components
     * @throws \Exception If the vectors do not have the same dimension or are not in the same tangent space
     */
    public function addTangentVectors(array $vector1, array $vector2, array $point): array
    {
        // Check if the vectors have the same dimension
        if (count($vector1) !== count($vector2)) {
            throw new \Exception('The tangent vectors must have the same dimension');
        }

        // Add the components of the two vectors
        $result = [];
        for ($i = 0; $i < count($vector1); $i++) {
            $result[] = $this->ath_bcadd($vector1[$i], $vector2[$i]);
        }

        return $result;
    }

    /**
     * Adds two vector spaces by summing all possible pairs of vectors.
     *
     * @param array $space1 The first vector space (each element is a vector represented as an array of components)
     * @param array $space2 The second vector space (each element is a vector represented as an array of components)
     * @return array The resulting vector space formed by the addition of the two spaces
     * @throws \Exception If the vectors do not have the same dimension
     */
    public function addVectorSpaces(array $space1, array $space2): array
    {
        // Get the dimension of the vectors in the first space
        $dim1 = count($space1[0]);

        // Check if all vectors in both spaces have the same dimension
        foreach ($space1 as $vector) {
            if (count($vector) !== $dim1) {
                throw new \Exception('All vectors in space1 must have the same dimension');
            }
        }
        foreach ($space2 as $vector) {
            if (count($vector) !== $dim1) {
                throw new \Exception('All vectors in space2 must have the same dimension as vectors in space1');
            }
        }

        // Create a set (array) to store the resulting space
        $resultSpace = [];

        // Sum all possible pairs of vectors from space1 and space2
        foreach ($space1 as $v1) {
            foreach ($space2 as $v2) {
                // Use the addVectors method to add the vectors component-wise
                $resultSpace[] = $this->addVectors($v1, $v2);
            }
        }

        // Optionally, we could remove duplicate vectors (depending on the context)
        return $resultSpace;
    }

    /**
     * Adds two mixed tensors (with covariant and contravariant indices) component-wise.
     *
     * @param array $tensor1 The first mixed tensor as a multidimensional array of numbers (string format)
     * @param array $tensor2 The second mixed tensor as a multidimensional array of numbers (string format)
     * @return array The resulting mixed tensor as a multidimensional array of numbers (string format)
     * @throws \Exception If the tensors do not have the same shape
     */
    public function addMixedTensors(array $tensor1, array $tensor2): array
    {
        // Check if the two tensors have the same shape
        if (!$this->checkSameShape($tensor1, $tensor2)) {
            throw new \Exception('Tensors must have the same shape');
        }

        // Perform component-wise addition
        return $this->addTensorsRecursively($tensor1, $tensor2);
    }

    /**
     * Adds two symbolic algebraic expressions by combining like terms.
     *
     * @param string $expression1 The first algebraic expression as a string
     * @param string $expression2 The second algebraic expression as a string
     * @return string The result of the symbolic addition
     */
    public function addSymbolicExpressions(string $expression1, string $expression2): string
    {
        // Parse the expressions into terms
        $terms1 = $this->parseExpression($expression1);
        $terms2 = $this->parseExpression($expression2);

        // Combine the terms
        $combinedTerms = $this->combineTerms($terms1, $terms2);

        // Reconstruct the expression
        return $this->reconstructExpression($combinedTerms);
    }

    /**
     * Adds two polynomials by summing like terms.
     *
     * @param string $polynomial1 The first polynomial as a string (e.g., '2x^2 + 3x - 5')
     * @param string $polynomial2 The second polynomial as a string (e.g., 'x^2 - x + 4')
     * @return string The result of the polynomial addition
     */
    public function addPolynomials(string $polynomial1, string $polynomial2): string
    {
        // Parse the polynomials into terms
        $terms1 = $this->parsePolynomial($polynomial1);
        $terms2 = $this->parsePolynomial($polynomial2);

        // Combine the terms
        $combinedTerms = $this->combinePolynomialTerms($terms1, $terms2);

        // Reconstruct the polynomial
        return $this->reconstructPolynomial($combinedTerms);
    }

    /**
     * Adds two symbolic expressions in a computational algebraic form.
     *
     * @param string $expression1 The first symbolic expression as a string
     * @param string $expression2 The second symbolic expression as a string
     * @return string The result of the symbolic addition
     */
    public function addSymbolicComputations(string $expression1, string $expression2): string
    {
        // Parse the expressions into terms
        $terms1 = $this->parseSymbolicExpression($expression1);
        $terms2 = $this->parseSymbolicExpression($expression2);

        // Combine the terms
        $combinedTerms = $this->combineSymbolicTerms($terms1, $terms2);

        // Reconstruct the symbolic expression
        return $this->reconstructSymbolicExpression($combinedTerms);
    }

    /**
     * Adds two orthogonal polynomials by combining like terms based on their degrees.
     *
     * @param array $polynomial1 The first polynomial as an array where the key is the degree and the value is the coefficient
     * @param array $polynomial2 The second polynomial as an array where the key is the degree and the value is the coefficient
     * @return array The resulting polynomial after addition
     */
    public function addOrthogonalPolynomials(array $polynomial1, array $polynomial2): array
    {
        // Combine the terms of both polynomials
        $combinedTerms = $this->combinePolynomialTerms($polynomial1, $polynomial2);

        // Reconstruct the polynomial from the combined terms
        return $this->reconstructOrthogonalPolynomial($combinedTerms);
    }

    /**
     * Adds two elements in a finite field (Z_p) using modular arithmetic.
     *
     * @param string $a The first element as a string
     * @param string $b The second element as a string
     * @param int $p The prime number defining the finite field Z_p
     * @return string The result of the addition modulo p
     * @throws \Exception If p is not a prime number or less than 2
     */
    public function addInFiniteField(string $a, string $b, int $p): string
    {
        $p = (string)$p;
        // Check if p is a valid prime number
        if ($p < 2 || !$this->isPrime($p)) {
            throw new \Exception("Invalid field: p must be a prime number greater than 1.");
        }

        // Perform the addition and compute the result modulo p
        $sum = $this->ath_bcadd($a, $b);  // Use arbitrary precision addition
        $modResult = $this->ath_bcmod($sum, (string)$p);  // Compute the result modulo p

        return $modResult;
    }

    /**
     * Performs the join (supremum) operation in a lattice structure.
     *
     * @param mixed $a The first element in the lattice
     * @param mixed $b The second element in the lattice
     * @param callable $joinFunction A function that defines how the join operation works for the lattice elements
     * @return mixed The result of the join operation
     */
    public function joinInLattice($a, $b, callable $joinFunction)
    {
        // Perform the join operation using the provided join function
        return $joinFunction($a, $b);
    }

    /**
     * Performs the meet (infimum) operation in a lattice structure.
     *
     * @param mixed $a The first element in the lattice
     * @param mixed $b The second element in the lattice
     * @param callable $meetFunction A function that defines how the meet operation works for the lattice elements
     * @return mixed The result of the meet operation
     */
    public function meetInLattice($a, $b, callable $meetFunction)
    {
        // Perform the meet operation using the provided meet function
        return $meetFunction($a, $b);
    }

    /**
     * Adds two elements in a topological group, ensuring the operation is continuous.
     *
     * @param mixed $a The first element in the topological group
     * @param mixed $b The second element in the topological group
     * @param callable $additionFunction A function that defines the addition in the topological group
     * @return mixed The result of the addition in the topological group
     */
    public function addInTopologicalGroup($a, $b, callable $additionFunction)
    {
        // Perform the addition using the provided addition function
        return $additionFunction($a, $b);
    }

    /**
     * Inverts an element in a topological group, ensuring the operation is continuous.
     *
     * @param mixed $a The element to invert in the topological group
     * @param callable $inverseFunction A function that defines the inversion in the topological group
     * @return mixed The inverse of the element
     */
    public function invertInTopologicalGroup($a, callable $inverseFunction)
    {
        // Perform the inversion using the provided inverse function
        return $inverseFunction($a);
    }

    /**
     * Adds two elements in a module over a ring R.
     *
     * @param array $vector1 The first element in the module (array of elements from M)
     * @param array $vector2 The second element in the module (array of elements from M)
     * @param callable $additionFunction A function that defines the addition in the module
     * @return array The result of the addition in the module
     * @throws \Exception If the two elements do not have the same length
     */
    public function addInModule(array $vector1, array $vector2, callable $additionFunction): array
    {
        // Check if the two vectors have the same length
        if (count($vector1) !== count($vector2)) {
            throw new \Exception('The elements must have the same length');
        }

        // Perform the addition component-wise
        $result = [];
        for ($i = 0; $i < count($vector1); $i++) {
            $result[] = $additionFunction($vector1[$i], $vector2[$i]);
        }

        return $result;
    }

    /**
     * Multiplies a scalar by an element in the module.
     *
     * @param mixed $scalar The scalar element from the ring R
     * @param array $vector The element in the module (array of elements from M)
     * @param callable $scalarMultiplicationFunction A function that defines the scalar multiplication in the module
     * @return array The result of the scalar multiplication
     */
    public function scalarMultiplyInModule($scalar, array $vector, callable $scalarMultiplicationFunction): array
    {
        // Perform the scalar multiplication component-wise
        $result = [];
        foreach ($vector as $component) {
            $result[] = $scalarMultiplicationFunction($scalar, $component);
        }

        return $result;
    }

    /**
     * Adds two elements in a Jordan algebra.
     *
     * @param mixed $a The first element in the Jordan algebra
     * @param mixed $b The second element in the Jordan algebra
     * @return mixed The result of the addition
     */
    public function addInJordanAlgebra($a, $b)
    {
        // Perform normal addition for the Jordan algebra
        return $this->ath_bcadd($a, $b);
    }

    /**
     * Multiplies two elements in a Jordan algebra.
     *
     * @param mixed $a The first element in the Jordan algebra
     * @param mixed $b The second element in the Jordan algebra
     * @return mixed The result of the Jordan product (a ◦ b)
     */
    public function jordanProduct($a, $b)
    {
        // Jordan product is commutative: a ◦ b = (a * b + b * a) / 2
        $productAB = $this->ath_bcmul($a, $b);
        $productBA = $this->ath_bcmul($b, $a);

        // Return the Jordan product (a ◦ b)
        return $this->ath_bcdiv($this->ath_bcadd($productAB, $productBA), '2');
    }

    /**
     * Verifies the Jordan identity for two elements in the Jordan algebra.
     *
     * @param mixed $a The first element
     * @param mixed $b The second element
     * @return bool True if the Jordan identity holds, false otherwise
     */
    public function verifyJordanIdentity($a, $b)
    {
        // Left-hand side: (a ◦ b) ◦ (a ◦ a)
        $aSquare = $this->jordanProduct($a, $a);
        $lhs = $this->jordanProduct($this->jordanProduct($a, $b), $aSquare);

        // Right-hand side: a ◦ (b ◦ (a ◦ a))
        $rhs = $this->jordanProduct($a, $this->jordanProduct($b, $aSquare));

        // Compare the left-hand side and the right-hand side
        return $lhs === $rhs;
    }

    /**
     * Performs the addition (join) in a Boolean lattice, which is the logical OR operation.
     *
     * @param int $a The first element in the Boolean lattice (0 or 1)
     * @param int $b The second element in the Boolean lattice (0 or 1)
     * @return int The result of the join (logical OR) operation
     * @throws \Exception If the inputs are not 0 or 1
     */
    public function addInBooleanLattice(int $a, int $b): int
    {
        // Ensure the elements are valid (0 or 1)
        if (!in_array($a, [0, 1]) || !in_array($b, [0, 1])) {
            throw new \Exception('Elements must be 0 or 1 in a Boolean lattice');
        }

        // Return the logical OR (join operation)
        return $a | $b;  // Using the bitwise OR operator
    }

    /**
     * Performs the meet operation (logical AND) in a Boolean lattice.
     *
     * @param int $a The first element in the Boolean lattice (0 or 1)
     * @param int $b The second element in the Boolean lattice (0 or 1)
     * @return int The result of the meet (logical AND) operation
     * @throws \Exception If the inputs are not 0 or 1
     */
    public function meetInBooleanLattice(int $a, int $b): int
    {
        // Ensure the elements are valid (0 or 1)
        if (!in_array($a, [0, 1]) || !in_array($b, [0, 1])) {
            throw new \Exception('Elements must be 0 or 1 in a Boolean lattice');
        }

        // Return the logical AND (meet operation)
        return $a & $b;  // Using the bitwise AND operator
    }

    /**
     * Complements an element in the Boolean lattice, which is the logical NOT operation.
     *
     * @param int $a The element in the Boolean lattice (0 or 1)
     * @return int The complement (logical NOT) of the element
     * @throws \Exception If the input is not 0 or 1
     */
    public function complementInBooleanLattice(int $a): int
    {
        // Ensure the element is valid (0 or 1)
        if (!in_array($a, [0, 1])) {
            throw new \Exception('Element must be 0 or 1 in a Boolean lattice');
        }

        // Return the logical NOT (complement operation)
        return $a === 0 ? 1 : 0;  // Logical NOT
    }

    /**
     * Adds two elements in an extended field E over a base field F.
     * Each element is represented as a tuple (a, b) where the element is a + b * sqrt(2).
     *
     * @param array $element1 The first element in the extended field (represented as [a, b])
     * @param array $element2 The second element in the extended field (represented as [a, b])
     * @return array The result of the addition, represented as [a, b]
     * @throws \Exception If the elements are not correctly formatted
     */
    public function addInExtendedField(array $element1, array $element2): array
    {
        // Ensure both elements are valid tuples of size 2
        if (count($element1) !== 2 || count($element2) !== 2) {
            throw new \Exception('Elements must be of the form [a, b] where a + b * sqrt(2)');
        }

        // Perform component-wise addition
        $realPart = $this->ath_bcadd($element1[0], $element2[0]);  // a + c
        $irrationalPart = $this->ath_bcadd($element1[1], $element2[1]);  // b + d

        // Return the result as a new element in the extended field
        return [$realPart, $irrationalPart];
    }

    /**
     * Multiplies two elements in an extended field E over a base field F.
     * Each element is represented as a tuple (a, b) where the element is a + b * sqrt(2).
     *
     * @param array $element1 The first element in the extended field (represented as [a, b])
     * @param array $element2 The second element in the extended field (represented as [a, b])
     * @return array The result of the multiplication, represented as [a, b]
     * @throws \Exception If the elements are not correctly formatted
     */
    public function multiplyInExtendedField(array $element1, array $element2): array
    {
        // Ensure both elements are valid tuples of size 2
        if (count($element1) !== 2 || count($element2) !== 2) {
            throw new \Exception('Elements must be of the form [a, b] where a + b * sqrt(2)');
        }

        // Perform the multiplication (a + b√2) * (c + d√2) = (ac + 2bd) + (ad + bc)√2
        $realPart = $this->ath_bcadd(
            $this->ath_bcmul($element1[0], $element2[0]),  // ac
            $this->ath_bcmul($element1[1], $element2[1], 2)  // 2bd
        );
        $irrationalPart = $this->ath_bcadd(
            $this->ath_bcmul($element1[0], $element2[1]),  // ad
            $this->ath_bcmul($element1[1], $element2[0])  // bc
        );

        // Return the result as a new element in the extended field
        return [$realPart, $irrationalPart];
    }

    /**
     * Adds two ordinal numbers (transfinite addition).
     * Ordinal addition is not commutative: omega + 1 is not the same as 1 + omega.
     *
     * @param string $ordinal1 The first ordinal (could be 'omega' or a natural number)
     * @param string $ordinal2 The second ordinal (could be 'omega' or a natural number)
     * @return string The result of the ordinal addition
     * @throws \Exception If invalid ordinals are provided
     */
    public function addOrdinals(string $ordinal1, string $ordinal2): string
    {
        // Special cases with omega
        if ($ordinal1 === 'omega') {
            return 'omega';  // omega + anything = omega (right additive identity for ordinals)
        }

        if ($ordinal2 === 'omega') {
            return $ordinal2;  // Anything + omega = omega
        }

        // Perform normal addition for finite natural numbers
        return $this->ath_bcadd($ordinal1, $ordinal2);
    }

    /**
     * Adds two cardinal numbers (transfinite addition).
     * Cardinal addition follows the rule: if kappa >= lambda, then kappa + lambda = kappa.
     *
     * @param string $cardinal1 The first cardinal (could be 'aleph_0' or a natural number)
     * @param string $cardinal2 The second cardinal (could be 'aleph_0' or a natural number)
     * @return string The result of the cardinal addition
     * @throws \Exception If invalid cardinals are provided
     */
    public function addCardinals(string $cardinal1, string $cardinal2): string
    {
        // Special cases with aleph_0
        if ($cardinal1 === 'aleph_0' || $cardinal2 === 'aleph_0') {
            return 'aleph_0';  // Any finite number + aleph_0 = aleph_0
        }

        // Perform normal addition for finite natural numbers
        return $this->ath_bcadd($cardinal1, $cardinal2);
    }

    /**
     * Adds two homomorphisms between groups G and H.
     * The homomorphisms are represented as functions that map elements from G to H.
     *
     * @param callable $homomorphism1 The first homomorphism (a function from G to H)
     * @param callable $homomorphism2 The second homomorphism (a function from G to H)
     * @return callable A new homomorphism that represents the sum of the two
     */
    public function addHomomorphisms(callable $homomorphism1, callable $homomorphism2): callable
    {
        // Return a new homomorphism that sums the results of the two homomorphisms
        return function ($element) use ($homomorphism1, $homomorphism2) {
            // Add the results of both homomorphisms for the given element
            return $this->ath_bcadd($homomorphism1($element), $homomorphism2($element));
        };
    }

    /**
     * Adds two elements in a Clifford algebra.
     * Each element is represented as an associative array where the keys are the types of terms (scalars, vectors, etc.).
     *
     * @param array $cliffordElement1 The first Clifford algebra element (e.g., ['scalar' => a0, 'v1' => a1, ...])
     * @param array $cliffordElement2 The second Clifford algebra element (e.g., ['scalar' => b0, 'v1' => b1, ...])
     * @return array The result of the addition, a new Clifford algebra element
     */
    public function addCliffordElements(array $cliffordElement1, array $cliffordElement2): array
    {
        $result = [];

        // Iterate through the terms of the first element
        foreach ($cliffordElement1 as $key => $value) {
            // Add corresponding terms from the second element
            if (isset($cliffordElement2[$key])) {
                $result[$key] = $this->ath_bcadd($value, $cliffordElement2[$key]);
            } else {
                $result[$key] = $value;  // If the term doesn't exist in the second element, keep it
            }
        }

        // Add any remaining terms from the second element that weren't in the first
        foreach ($cliffordElement2 as $key => $value) {
            if (!isset($cliffordElement1[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Composes two automorphisms in a Galois group (representing the "sum" in terms of function composition).
     *
     * @param callable $automorphism1 The first automorphism (function from K to K)
     * @param callable $automorphism2 The second automorphism (function from K to K)
     * @return callable A new automorphism that represents the composition of the two
     */
    public function composeAutomorphisms(callable $automorphism1, callable $automorphism2): callable
    {
        // Return the composition of the two automorphisms
        return function ($element) use ($automorphism1, $automorphism2) {
            // Compose the automorphisms: (automorphism2 o automorphism1)(element)
            return $automorphism2($automorphism1($element));
        };
    }

    /**
     * Applies an automorphism to an element in a Galois extension.
     *
     * @param callable $automorphism The automorphism (function from K to K)
     * @param mixed $element The element in the extension field K
     * @return mixed The result of applying the automorphism to the element
     */
    public function applyAutomorphism(callable $automorphism, $element)
    {
        return $automorphism($element);
    }

    /**
     * Adds two surreal numbers represented as {L | R}, where L and R are arrays of surreal numbers.
     *
     * @param array $surreal1 The first surreal number (represented as [L, R])
     * @param array $surreal2 The second surreal number (represented as [L, R])
     * @return array The result of the addition, a new surreal number {L | R}
     */
    public function addSurrealNumbers(array $surreal1, array $surreal2): array
    {
        list($L1, $R1) = $surreal1;  // Left and right sets of the first surreal number
        list($L2, $R2) = $surreal2;  // Left and right sets of the second surreal number

        $newLeft = [];  // Set of new left elements for the result
        $newRight = [];  // Set of new right elements for the result

        // Add the left elements of the first number to the second number
        foreach ($L1 as $L1Element) {
            $newLeft[] = $this->addSurrealNumbers($L1Element, $surreal2);
        }

        // Add the first number to the left elements of the second number
        foreach ($L2 as $L2Element) {
            $newLeft[] = $this->addSurrealNumbers($surreal1, $L2Element);
        }

        // Add the right elements of the first number to the second number
        foreach ($R1 as $R1Element) {
            $newRight[] = $this->addSurrealNumbers($R1Element, $surreal2);
        }

        // Add the first number to the right elements of the second number
        foreach ($R2 as $R2Element) {
            $newRight[] = $this->addSurrealNumbers($surreal1, $R2Element);
        }

        return [$newLeft, $newRight];  // Return the new surreal number as {L | R}
    }

    /**
     * Differentiates a sum of functions composed with the chain rule.
     * Each function is represented as a callable that takes the variable and returns its value.
     *
     * @param array $functions Array of functions to differentiate
     * @param callable $innerFunction The inner function (composed with each function)
     * @param callable $innerDerivative The derivative of the inner function
     * @return callable A function representing the derivative of the sum of composed functions
     */
    public function differentiateSumWithChainRule(array $functions, callable $innerFunction, callable $innerDerivative): callable
    {
        return function ($x) use ($functions, $innerFunction, $innerDerivative) {
            $result = 0;

            // Differentiate each function using the chain rule and sum the results
            foreach ($functions as $function) {
                // Apply the chain rule: f'(g(x)) * g'(x)
                $result += $function($innerFunction($x)) * $innerDerivative($x);
            }

            return $result;
        };
    }

    /**
     * Adds two elements in a Grassmann algebra.
     * Each element is represented as a set of blades, where a blade is a wedge product.
     *
     * @param array $element1 The first element in the Grassmann algebra (represented as a blade or combination of blades)
     * @param array $element2 The second element in the Grassmann algebra (represented as a blade or combination of blades)
     * @return array The result of the addition, a new element in the Grassmann algebra
     */
    public function addGrassmannElements(array $element1, array $element2): array
    {
        $result = [];

        // Add corresponding blades together
        foreach ($element1 as $key => $value) {
            if (isset($element2[$key])) {
                // Add the blade values using the ath_bcadd function for arbitrary precision
                $result[$key] = $this->ath_bcadd($value, $element2[$key]);
            } else {
                // If the blade does not exist in the second element, keep the first one
                $result[$key] = $value;
            }
        }

        // Add remaining blades from the second element that are not in the first
        foreach ($element2 as $key => $value) {
            if (!isset($element1[$key])) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Wedge product between two vectors in a Grassmann algebra.
     *
     * @param array $vector1 The first vector
     * @param array $vector2 The second vector
     * @return array The result of the wedge product
     */
    public function wedgeProduct(array $vector1, array $vector2): array
    {
        // The wedge product follows anticommutativity: v1 ^ v2 = -v2 ^ v1
        return [
            'bivector' => $this->ath_bcmul($vector1['magnitude'], $vector2['magnitude']) // Multiply magnitudes as an example
        ];
    }

    /**
     * Adds two elements in the complex numbers (C).
     * Each element is represented as a pair of real and imaginary components.
     *
     * @param array $complex1 The first complex number (represented as [real, imaginary])
     * @param array $complex2 The second complex number (represented as [real, imaginary])
     * @return array The result of the addition, a new complex number
     */
    public function addComplex(array $complex1, array $complex2): array
    {
        return [
            'real' => $this->ath_bcadd($complex1['real'], $complex2['real']),
            'imaginary' => $this->ath_bcadd($complex1['imaginary'], $complex2['imaginary'])
        ];
    }

    /**
     * Adds two quaternions (H).
     * Each quaternion is represented as a combination of 1, i, j, k components.
     *
     * @param array $quaternion1 The first quaternion (represented as [a, b, c, d])
     * @param array $quaternion2 The second quaternion (represented as [a, b, c, d])
     * @return array The result of the addition, a new quaternion
     */
    public function addQuaternions(array $quaternion1, array $quaternion2): array
    {
        return [
            'a' => $this->ath_bcadd($quaternion1['a'], $quaternion2['a']),  // Scalar part
            'b' => $this->ath_bcadd($quaternion1['b'], $quaternion2['b']),  // i component
            'c' => $this->ath_bcadd($quaternion1['c'], $quaternion2['c']),  // j component
            'd' => $this->ath_bcadd($quaternion1['d'], $quaternion2['d'])   // k component
        ];
    }

    /**
     * Adds two octonions (O), also known as Cayley numbers.
     * Each octonion is represented as a combination of 1, e1, e2, e3, e4, e5, e6, e7 components.
     *
     * @param array $octonion1 The first octonion (represented as [a, e1, e2, e3, e4, e5, e6, e7])
     * @param array $octonion2 The second octonion (represented as [a, e1, e2, e3, e4, e5, e6, e7])
     * @return array The result of the addition, a new octonion
     */
    public function addOctonions(array $octonion1, array $octonion2): array
    {
        return [
            'a' => $this->ath_bcadd($octonion1['a'], $octonion2['a']),      // Scalar part
            'e1' => $this->ath_bcadd($octonion1['e1'], $octonion2['e1']),   // e1 component
            'e2' => $this->ath_bcadd($octonion1['e2'], $octonion2['e2']),   // e2 component
            'e3' => $this->ath_bcadd($octonion1['e3'], $octonion2['e3']),   // e3 component
            'e4' => $this->ath_bcadd($octonion1['e4'], $octonion2['e4']),   // e4 component
            'e5' => $this->ath_bcadd($octonion1['e5'], $octonion2['e5']),   // e5 component
            'e6' => $this->ath_bcadd($octonion1['e6'], $octonion2['e6']),   // e6 component
            'e7' => $this->ath_bcadd($octonion1['e7'], $octonion2['e7'])    // e7 component
        ];
    }

    /**
     * Adds two numeric series term by term.
     * Each series is represented as an array of terms, and the function sums them term-wise.
     *
     * @param array $series1 The first numeric series (array of numbers as strings)
     * @param array $series2 The second numeric series (array of numbers as strings)
     * @return array The result of the term-by-term addition of the two series
     * @throws \Exception If the two series do not have the same length
     */
    public function addSeries(array $series1, array $series2): array
    {
        // Check if the two series have the same length
        if (count($series1) !== count($series2)) {
            throw new \Exception('The series must have the same length');
        }

        // Initialize an empty array for the result
        $result = [];

        // Perform term-by-term addition
        for ($i = 0; $i < count($series1); $i++) {
            // Add corresponding terms using ath_bcadd
            $result[] = $this->ath_bcadd($series1[$i], $series2[$i]);
        }

        return $result;
    }

    /**
     * Sums the first n terms of a numeric series.
     *
     * @param array $series The numeric series (array of numbers as strings)
     * @param int $n The number of terms to sum
     * @return string The result of the sum as a string
     * @throws \Exception If n exceeds the length of the series
     */
    public function sumSeries(array $series, int $n): string
    {
        // Check if n is valid
        if ($n > count($series)) {
            throw new \Exception('n exceeds the length of the series');
        }

        $sum = '0';

        // Sum the first n terms
        for ($i = 0; $i < $n; $i++) {
            $sum = $this->ath_bcadd($sum, $series[$i]);
        }

        return $sum;
    }

    /**
     * Adds two powers using logarithmic properties to simplify the expression.
     *
     * @param string $base1 The base of the first power
     * @param string $exp1 The exponent of the first power
     * @param string $base2 The base of the second power
     * @param string $exp2 The exponent of the second power
     * @return string The simplified result of the addition as a string
     * @throws \Exception If the exponents are not equal
     */
    public function addPowersWithLogs(string $base1, string $exp1, string $base2, string $exp2): string
    {
        // Check if exponents are the same, otherwise return a message
        if ($exp1 !== $exp2) {
            throw new \Exception('Exponents must be equal for this simplification to work');
        }

        // Compute the sum of powers a^x + b^x as a^x(1 + (b/a)^x)
        $commonExp = $exp1;
        $logBaseRatio = $this->ath_bclog($this->ath_bcdiv($base2, $base1));  // log(b/a)
        $logSum = $this->ath_bclog($this->ath_bcadd('1', $logBaseRatio));  // log(1 + (b/a)^x)

        // Use logarithmic properties to compute a^x + b^x
        $result = $this->ath_bcpow($base1, $commonExp) . ' * ' . $logSum;

        return $result;
    }

    /**
     * Uses logarithmic properties to simplify the product of two terms raised to powers.
     *
     * @param string $base1 The base of the first power
     * @param string $exp1 The exponent of the first power
     * @param string $base2 The base of the second power
     * @param string $exp2 The exponent of the second power
     * @return string The product of the powers, simplified using logarithms
     */
    public function multiplyPowersWithLogs(string $base1, string $exp1, string $base2, string $exp2): string
    {
        // Compute the product of two powers a^x * b^x = (ab)^x
        $logBaseProduct = $this->ath_bclog($this->ath_bcmul($base1, $base2));  // log(ab)
        $result = $this->ath_bcpow($logBaseProduct, $exp1);

        return $result;
    }

    /**
     * Adds two Fourier series represented by their Fourier coefficients.
     * The result is a new Fourier series with the sum of corresponding coefficients.
     *
     * @param array $series1 The first Fourier series coefficients ['a' => [...], 'b' => [...]]
     * @param array $series2 The second Fourier series coefficients ['a' => [...], 'b' => [...]]
     * @return array The result of the addition, a new Fourier series
     */
    public function addFourierSeries(array $series1, array $series2): array
    {
        // Ensure the two series have the same length
        $n1 = count($series1['a']);
        $n2 = count($series2['a']);
        $maxLength = max($n1, $n2);

        $result = [
            'a' => [],
            'b' => []
        ];

        // Sum the 'a' coefficients (cosine terms)
        for ($n = 0; $n < $maxLength; $n++) {
            $a1 = $n < $n1 ? $series1['a'][$n] : '0';
            $a2 = $n < $n2 ? $series2['a'][$n] : '0';
            $result['a'][] = $this->ath_bcadd($a1, $a2);
        }

        // Sum the 'b' coefficients (sine terms)
        for ($n = 0; $n < $maxLength; $n++) {
            $b1 = $n < $n1 ? $series1['b'][$n] : '0';
            $b2 = $n < $n2 ? $series2['b'][$n] : '0';
            $result['b'][] = $this->ath_bcadd($b1, $b2);
        }

        return $result;
    }

    /**
     * Evaluates a Fourier series at a given point x.
     *
     * @param array $series The Fourier series coefficients ['a' => [...], 'b' => [...]]
     * @param string $x The point at which to evaluate the series
     * @return string The value of the Fourier series at x
     */
    public function evaluateFourierSeries(array $series, string $x): string
    {
        $sum = '0';

        // Add the constant term a_0 / 2
        if (!empty($series['a'])) {
            $sum = bcdiv($series['a'][0], '2');
        }

        // Add the cosine and sine terms
        for ($n = 1; $n < count($series['a']); $n++) {
            $cosTerm = bcmul($series['a'][$n], (string)cos($n * (float)$x));
            $sinTerm = bcmul($series['b'][$n], (string)sin($n * (float)$x));
            $sum = bcadd($sum, bcadd($cosTerm, $sinTerm));
        }

        return $sum;
    }

    /**
     * Adds two logarithmic values using their base to convert them to linear values, sum them, and return the result in logarithmic scale.
     *
     * @param string $log1 The first logarithmic value (in string format)
     * @param string $log2 The second logarithmic value (in string format)
     * @param string $base The base of the logarithms (default is '10' for base-10 logarithms)
     * @return string The result of the logarithmic addition as a string
     */
    public function addLogarithmic(string $log1, string $log2, string $base = '10'): string
    {
        // Convert the log values back to linear scale: A = base^log1, B = base^log2
        $linear1 = $this->ath_bcpow($base, $log1);
        $linear2 = $this->ath_bcpow($base, $log2);

        // Sum the linear values
        $linearSum = $this->ath_bcadd($linear1, $linear2);

        // Convert the sum back to logarithmic scale: log_b(S)
        return $this->ath_bclog($linearSum, (int)$base);
    }

    /**
     * Converts a logarithmic value back to linear scale.
     *
     * @param string $log The logarithmic value
     * @param string $base The base of the logarithm (default: '10')
     * @return string The corresponding linear value
     */
    public function logToLinear(string $log, string $base = '10'): string
    {
        return $this->ath_bcpow($base, $log);
    }

    /**
     * Converts a linear value to logarithmic scale.
     *
     * @param string $linear The linear value
     * @param string $base The base for the logarithm (default: '10')
     * @return string The logarithmic value
     */
    public function linearToLog(string $linear, string $base = '10'): string
    {
        return $this->ath_bclog($linear, (int)$base);
    }

    /**
     * Computes the binomial coefficient (n choose k) using arbitrary precision arithmetic.
     *
     * @param string $n The total number of elements (n)
     * @param string $k The number of elements to choose (k)
     * @return string The binomial coefficient as a string
     */
    public function binomialCoefficient(string $n, string $k): string
    {
        // If k > n, the binomial coefficient is 0
        if ($this->ath_bccomp($k, $n) > 0) {
            return '0';
        }

        // If k == 0 or k == n, the binomial coefficient is 1
        if ($this->ath_bccomp($k, '0') === 0 || $this->ath_bccomp($k, $n) === 0) {
            return '1';
        }

        // Calculate n! / (k! * (n-k)!)
        $nFactorial = $this->factorial($n);
        $kFactorial = $this->factorial($k);
        $nMinusKFactorial = $this->factorial($this->ath_bcsub($n, $k));

        // Return binomial coefficient: n! / (k! * (n-k)!)
        return $this->ath_bcdiv($nFactorial, $this->ath_bcmul($kFactorial, $nMinusKFactorial));
    }

    /**
     * Adds two binomial coefficients: binomial(n, k) + binomial(n, k-1).
     * This uses Pascal's identity: binomial(n, k) + binomial(n, k-1) = binomial(n+1, k).
     *
     * @param string $n The total number of elements (n)
     * @param string $k The number of elements to choose (k)
     * @return string The result of the binomial addition as a string
     */
    public function addBinomialCoefficients(string $n, string $k): string
    {
        // Calculate binomial(n, k) + binomial(n, k-1) using Pascal's identity
        $binomial1 = $this->binomialCoefficient($n, $k);
        $binomial2 = $this->binomialCoefficient($n, $this->ath_bcsub($k, '1'));

        // Return the sum of the two binomial coefficients
        return $this->ath_bcadd($binomial1, $binomial2);
    }

    /**
     * Adds two Laurent series, represented by two arrays of coefficients.
     * One array represents positive powers of z, the other represents negative powers of z.
     *
     * @param array $laurent1 The first Laurent series coefficients ['positive' => [...], 'negative' => [...]]
     * @param array $laurent2 The second Laurent series coefficients ['positive' => [...], 'negative' => [...]]
     * @return array The result of the addition, a new Laurent series
     */
    public function addLaurentSeries(array $laurent1, array $laurent2): array
    {
        // Add the positive powers of z
        $positiveResult = $this->addCoefficientArrays($laurent1['positive'], $laurent2['positive']);

        // Add the negative powers of z
        $negativeResult = $this->addCoefficientArrays($laurent1['negative'], $laurent2['negative']);

        return [
            'positive' => $positiveResult,
            'negative' => $negativeResult
        ];
    }

    /**
     * Evaluates a Laurent series at a given point z.
     *
     * @param array $laurent The Laurent series coefficients ['positive' => [...], 'negative' => [...]]
     * @param string $z The point at which to evaluate the series (in string format)
     * @return string The value of the Laurent series at z
     */
    public function evaluateLaurentSeries(array $laurent, string $z): string
    {
        $sum = '0';

        // Evaluate the positive powers of z
        for ($n = 0; $n < count($laurent['positive']); $n++) {
            $zPower = $this->ath_bcpow($z, (string)$n);
            $term = $this->ath_bcmul($laurent['positive'][$n], $zPower);
            $sum = $this->ath_bcadd($sum, $term);
        }

        // Evaluate the negative powers of z
        for ($n = 1; $n <= count($laurent['negative']); $n++) {
            $zPower = $this->ath_bcpow($z, (string)(-1 * $n));
            $term = $this->ath_bcmul($laurent['negative'][$n - 1], $zPower);
            $sum = $this->ath_bcadd($sum, $term);
        }

        return $sum;
    }

    /**
     * Adds two modular functions represented as Fourier series.
     * Each series is represented by an array of coefficients for the terms in the series.
     *
     * @param array $modular1 The first modular function Fourier coefficients ['a' => [...], 'b' => [...]]
     * @param array $modular2 The second modular function Fourier coefficients ['a' => [...], 'b' => [...]]
     * @return array The result of the addition, a new modular function (Fourier series)
     */
    public function addModularFunctions(array $modular1, array $modular2): array
    {
        // Add the Fourier coefficients for the two functions
        $aResult = $this->addCoefficientArrays($modular1['a'], $modular2['a']);
        $bResult = $this->addCoefficientArrays($modular1['b'], $modular2['b']);

        return [
            'a' => $aResult,  // Cosine terms
            'b' => $bResult   // Sine terms
        ];
    }

    /**
     * Evaluates a modular function at a given point tau (in the upper half-plane).
     *
     * @param array $modular The modular function Fourier coefficients ['a' => [...], 'b' => [...]]
     * @param string $tau The point at which to evaluate the series (as a complex number in string format)
     * @return string The value of the modular function at tau
     */
    public function evaluateModularFunction(array $modular, string $tau): string
    {
        $sum = '0';

        // Evaluate the cosine terms (a_n cos(n tau))
        for ($n = 0; $n < count($modular['a']); $n++) {
            $cosTerm = bcmul($modular['a'][$n], (string)cos($n * (float)$tau));
            $sum = $this->ath_bcadd($sum, $cosTerm);
        }

        // Evaluate the sine terms (b_n sin(n tau))
        for ($n = 0; $n < count($modular['b']); $n++) {
            $sinTerm = bcmul($modular['b'][$n], (string)sin($n * (float)$tau));
            $sum = $this->ath_bcadd($sum, $sinTerm);
        }

        return $sum;
    }

    /**
     * Adds two Diophantine equations of the form ax + by = c.
     *
     * @param array $equation1 The first Diophantine equation as ['a' => ..., 'b' => ..., 'c' => ...]
     * @param array $equation2 The second Diophantine equation as ['a' => ..., 'b' => ..., 'c' => ...]
     * @return array The resulting Diophantine equation after addition
     */
    public function addDiophantineEquations(array $equation1, array $equation2): array
    {
        // Sommare i coefficienti delle due equazioni
        $aResult = $this->ath_bcadd($equation1['a'], $equation2['a']);
        $bResult = $this->ath_bcadd($equation1['b'], $equation2['b']);
        $cResult = $this->ath_bcadd($equation1['c'], $equation2['c']);

        // Restituisce la nuova equazione
        return [
            'a' => $aResult,
            'b' => $bResult,
            'c' => $cResult
        ];
    }

    /**
     * Solves a linear Diophantine equation of the form ax + by = c using the extended Euclidean algorithm.
     * Returns one particular solution, and the general form of the solution.
     *
     * @param string $a Coefficient of x
     * @param string $b Coefficient of y
     * @param string $c Constant term
     * @return array A particular solution for x and y, and the general form of the solution
     * @throws \Exception If no solution exists
     */
    public function solveDiophantine(string $a, string $b, string $c): array
    {
        // Calcola il massimo comun divisore di a e b usando l'algoritmo di Euclide esteso
        list($gcd, $x0, $y0) = $this->extendedEuclidean($a, $b);

        // Verifica se c è divisibile per gcd(a, b)
        if ($this->ath_bcmod($c, $gcd) !== '0') {
            throw new \Exception('No solution exists for the given Diophantine equation');
        }

        // Moltiplica la soluzione particolare per c / gcd(a, b)
        $multiplier = $this->ath_bcdiv($c, $gcd);
        $x0 = $this->ath_bcmul($x0, $multiplier);
        $y0 = $this->ath_bcmul($y0, $multiplier);

        // Restituisce una soluzione particolare e la forma generale della soluzione
        return [
            'particular' => ['x' => $x0, 'y' => $y0],
            'general' => ['x' => "$x0 + k*($b/$gcd)", 'y' => "$y0 - k*($a/$gcd)"]
        ];
    }

    /**
     * Adds two generating functions represented as arrays of coefficients.
     * The result is a new generating function with coefficients that are the sum of the original ones.
     *
     * @param array $function1 The first generating function coefficients [a_0, a_1, a_2, ...]
     * @param array $function2 The second generating function coefficients [b_0, b_1, b_2, ...]
     * @return array The result of the addition, a new generating function
     */
    public function addGeneratingFunctions(array $function1, array $function2): array
    {
        // Determina la lunghezza massima tra le due funzioni generatrici
        $maxLength = max(count($function1), count($function2));

        // Risultato come array di coefficienti
        $result = [];

        // Somma dei coefficienti delle due funzioni
        for ($n = 0; $n < $maxLength; $n++) {
            $a_n = $n < count($function1) ? $function1[$n] : '0';
            $b_n = $n < count($function2) ? $function2[$n] : '0';

            $result[] = $this->ath_bcadd($a_n, $b_n);
        }

        return $result;
    }

    /**
     * Evaluates a generating function at a specific value of x.
     *
     * @param array $function The generating function coefficients [a_0, a_1, a_2, ...]
     * @param string $x The value of x (in string format)
     * @return string The evaluated value of the generating function at x
     */
    public function evaluateGeneratingFunction(array $function, string $x): string
    {
        $sum = '0';

        // Somma i termini della funzione generatrice valutata in x
        for ($n = 0; $n < count($function); $n++) {
            $xPower = $this->ath_bcpow($x, (string)$n);  // x^n
            $term = $this->ath_bcmul($function[$n], $xPower);  // a_n * x^n
            $sum = $this->ath_bcadd($sum, $term);  // Somma i termini
        }

        return $sum;
    }

    /**
     * Adds two Riemann zeta-like functions, represented by the general zeta series terms.
     * The first function is the Riemann zeta function itself, and the second is any zeta-like series.
     *
     * @param array $series1 The first zeta-like series coefficients (default Riemann zeta: all ones)
     * @param array $series2 The second zeta-like series coefficients
     * @param string $s The value of s (as a string, typically a complex number)
     * @param int $terms The number of terms to compute for the series (to simulate the infinite sum)
     * @return string The result of the addition, a new zeta-like series evaluated at s
     */
    public function addZetaFunctions(array $series1, array $series2, string $s, int $terms = 1000): string
    {
        $sum = '0';

        // Sommare i termini della serie
        for ($n = 1; $n <= $terms; $n++) {
            $term1 = $n < count($series1) ? $series1[$n - 1] : '1';  // Coefficiente della prima funzione (zeta di Riemann ha tutti 1)
            $term2 = $n < count($series2) ? $series2[$n - 1] : '0';  // Coefficiente della seconda serie

            // Somma dei coefficienti per il termine n^(-s)
            $coeffSum = $this->ath_bcadd($term1, $term2);

            // Calcolo del termine (n^(-s))
            $nPower = $this->ath_bcpow((string)$n, $s);

            // Calcolo del termine della somma
            $sumTerm = $this->ath_bcdiv($coeffSum, $nPower);

            // Sommare il termine alla somma totale
            $sum = $this->ath_bcadd($sum, $sumTerm);
        }

        return $sum;
    }

    /**
     * Evaluates the Riemann zeta function for a given s and number of terms in the series.
     *
     * @param string $s The value of s (as a string, typically a complex number)
     * @param int $terms The number of terms to compute for the series (to simulate the infinite sum)
     * @return string The evaluated value of the Riemann zeta function at s
     */
    public function zetaRiemann(string $s, int $terms = 1000): string
    {
        // Usa la funzione addZetaFunctions con una serie di "1" (funzione zeta di Riemann standard)
        $riemannSeries = array_fill(0, $terms, '1');  // Coefficienti tutti 1 per la zeta di Riemann
        return $this->addZetaFunctions($riemannSeries, [], $s, $terms);
    }

    /**
     * Computes the multinomial coefficient for given n and partitions k1, k2, ..., kr.
     *
     * @param string $n The total number of elements (n)
     * @param array $partitions The partitions of n (k1, k2, ..., kr)
     * @return string The multinomial coefficient as a string
     */
    public function multinomialCoefficient(string $n, array $partitions): string
    {
        // Calcola n!
        $nFactorial = $this->factorial($n);

        // Calcola il prodotto dei fattoriali delle partizioni k1!, k2!, ..., kr!
        $partitionFactorialProduct = '1';
        foreach ($partitions as $partition) {
            $partitionFactorialProduct = $this->ath_bcmul($partitionFactorialProduct, $this->factorial($partition));
        }

        // Restituisce il coefficiente multinomiale: n! / (k1! * k2! * ... * kr!)
        return $this->ath_bcdiv($nFactorial, $partitionFactorialProduct);
    }

    /**
     * Adds two multinomial coefficients by summing their partitions.
     * The result is a multinomial coefficient based on the sum of partitions.
     *
     * @param string $n The total number of elements (n)
     * @param array $partitions1 The first set of partitions (k1, k2, ..., kr)
     * @param array $partitions2 The second set of partitions (k1, k2, ..., kr)
     * @return string The result of the multinomial addition as a string
     */
    public function addMultinomialCoefficients(string $n, array $partitions1, array $partitions2): string
    {
        // Sommare le partizioni corrispondenti
        $summedPartitions = [];
        $maxPartitions = max(count($partitions1), count($partitions2));

        for ($i = 0; $i < $maxPartitions; $i++) {
            $k1 = $i < count($partitions1) ? $partitions1[$i] : '0';
            $k2 = $i < count($partitions2) ? $partitions2[$i] : '0';

            $summedPartitions[] = $this->ath_bcadd($k1, $k2);
        }

        // Calcola il coefficiente multinomiale per le partizioni sommate
        return $this->multinomialCoefficient($n, $summedPartitions);
    }

    /**
     * Computes the union of two sets.
     *
     * @param array $set1 The first set
     * @param array $set2 The second set
     * @return array The union of the two sets
     */
    public function unionSets(array $set1, array $set2): array
    {
        // Unione degli insiemi eliminando i duplicati
        return array_values(array_unique(array_merge($set1, $set2)));
    }

    /**
     * Computes the disjoint union of two sets.
     * Elements from the first set are labeled with 0, and elements from the second set with 1.
     *
     * @param array $set1 The first set
     * @param array $set2 The second set
     * @return array The disjoint union of the two sets
     */
    public function disjointUnionSets(array $set1, array $set2): array
    {
        $disjointUnion = [];

        // Aggiungere gli elementi del primo insieme con etichetta 0
        foreach ($set1 as $element) {
            $disjointUnion[] = [$element, 0];
        }

        // Aggiungere gli elementi del secondo insieme con etichetta 1
        foreach ($set2 as $element) {
            $disjointUnion[] = [$element, 1];
        }

        return $disjointUnion;
    }

    /**
     * Performs boolean addition (logical OR) between two boolean values.
     *
     * @param bool $bool1 The first boolean value
     * @param bool $bool2 The second boolean value
     * @return bool The result of the boolean addition (OR)
     */
    public function addBoolean(bool $bool1, bool $bool2): bool
    {
        return $bool1 || $bool2;
    }

    /**
     * Performs boolean addition (logical OR) over an array of boolean values.
     *
     * @param array $boolArray An array of boolean values
     * @return bool The result of the boolean addition over the array
     */
    public function addBooleanArray(array $boolArray): bool
    {
        $result = false;

        foreach ($boolArray as $boolValue) {
            $result = $this->addBoolean($result, $boolValue);
        }

        return $result;
    }

    /**
     * Performs fuzzy addition (fuzzy union) between two fuzzy sets.
     *
     * @param array $set1 The first fuzzy set, an associative array where keys are elements and values are membership degrees
     * @param array $set2 The second fuzzy set, an associative array where keys are elements and values are membership degrees
     * @return array The resulting fuzzy set after fuzzy addition (union)
     */
    public function addFuzzy(array $set1, array $set2): array
    {
        $result = [];

        // Unione delle chiavi di entrambi gli insiemi
        $elements = array_unique(array_merge(array_keys($set1), array_keys($set2)));

        // Calcolo del grado di appartenenza massimo per ogni elemento
        foreach ($elements as $element) {
            $membership1 = isset($set1[$element]) ? $set1[$element] : 0;
            $membership2 = isset($set2[$element]) ? $set2[$element] : 0;

            // Prendere il massimo grado di appartenenza
            $result[$element] = max($membership1, $membership2);
        }

        return $result;
    }

    /**
     * Performs fuzzy addition (fuzzy union) over an array of fuzzy sets.
     *
     * @param array $fuzzySets An array of fuzzy sets (each fuzzy set is an associative array)
     * @return array The resulting fuzzy set after fuzzy addition over all sets
     */
    public function addFuzzyMultiple(array $fuzzySets): array
    {
        $result = [];

        foreach ($fuzzySets as $fuzzySet) {
            $result = $this->addFuzzy($result, $fuzzySet);
        }

        return $result;
    }

    /**
     * Performs fuzzy addition (fuzzy union) between two fuzzy sets using the maximum membership value.
     * This simulates the combination of information from multiple fuzzy sources using fuzzy logic.
     *
     * @param array $set1 The first fuzzy set, an associative array where keys are elements and values are membership degrees
     * @param array $set2 The second fuzzy set, an associative array where keys are elements and values are membership degrees
     * @return array The resulting fuzzy set after fuzzy addition (union)
     */
    public function addFuzzyUnion(array $set1, array $set2): array
    {
        $result = [];

        // Unione delle chiavi di entrambi gli insiemi
        $elements = array_unique(array_merge(array_keys($set1), array_keys($set2)));

        // Calcolo del grado di appartenenza massimo per ogni elemento
        foreach ($elements as $element) {
            $membership1 = isset($set1[$element]) ? $set1[$element] : 0;
            $membership2 = isset($set2[$element]) ? $set2[$element] : 0;

            // Prendere il massimo grado di appartenenza
            $result[$element] = max($membership1, $membership2);
        }

        return $result;
    }

    /**
     * Performs fuzzy addition using the average membership value across multiple fuzzy sets.
     * This simulates combining information from different fuzzy sources by averaging their membership degrees.
     *
     * @param array $fuzzySets An array of fuzzy sets (each fuzzy set is an associative array)
     * @return array The resulting fuzzy set after fuzzy addition (average)
     */
    public function addFuzzyAverage(array $fuzzySets): array
    {
        $result = [];
        $elementCounts = [];

        // Itera su ogni insieme fuzzy
        foreach ($fuzzySets as $fuzzySet) {
            foreach ($fuzzySet as $element => $membership) {
                // Somma dei gradi di appartenenza
                if (!isset($result[$element])) {
                    $result[$element] = 0;
                    $elementCounts[$element] = 0;
                }
                $result[$element] += $membership;
                $elementCounts[$element]++;
            }
        }

        // Calcolo della media
        foreach ($result as $element => $totalMembership) {
            $result[$element] = $totalMembership / $elementCounts[$element];
        }

        return $result;
    }

    /**
     * Performs a bitwise AND operation between two integers.
     *
     * @param int $num1 The first integer
     * @param int $num2 The second integer
     * @return int The result of the bitwise AND operation
     */
    public function addBitwiseAND(int $num1, int $num2): int
    {
        return $num1 & $num2;
    }

    /**
     * Performs a bitwise AND operation between two arrays of binary values (represented as strings of 1s and 0s).
     *
     * @param array $binaryArray1 The first array of binary values
     * @param array $binaryArray2 The second array of binary values
     * @return array The result of the bitwise AND operation on the arrays
     */
    public function addBitwiseANDArray(array $binaryArray1, array $binaryArray2): array
    {
        $result = [];
        $maxLength = max(count($binaryArray1), count($binaryArray2));

        for ($i = 0; $i < $maxLength; $i++) {
            // Ottieni i bit corrispondenti da ciascun array (in caso di lunghezza diversa, considera 0)
            $bit1 = $i < count($binaryArray1) ? $binaryArray1[$i] : '0';
            $bit2 = $i < count($binaryArray2) ? $binaryArray2[$i] : '0';

            // Esegui l'operazione AND bitwise
            $result[] = ($bit1 === '1' && $bit2 === '1') ? '1' : '0';
        }

        return $result;
    }

    /**
     * Performs a bitwise OR operation between two integers.
     *
     * @param int $num1 The first integer
     * @param int $num2 The second integer
     * @return int The result of the bitwise OR operation
     */
    public function addBitwiseOR(int $num1, int $num2): int
    {
        return $num1 | $num2;
    }

    /**
     * Performs a bitwise OR operation between two arrays of binary values (represented as strings of 1s and 0s).
     *
     * @param array $binaryArray1 The first array of binary values
     * @param array $binaryArray2 The second array of binary values
     * @return array The result of the bitwise OR operation on the arrays
     */
    public function addBitwiseORArray(array $binaryArray1, array $binaryArray2): array
    {
        $result = [];
        $maxLength = max(count($binaryArray1), count($binaryArray2));

        for ($i = 0; $i < $maxLength; $i++) {
            // Ottieni i bit corrispondenti da ciascun array (in caso di lunghezza diversa, considera 0)
            $bit1 = $i < count($binaryArray1) ? $binaryArray1[$i] : '0';
            $bit2 = $i < count($binaryArray2) ? $binaryArray2[$i] : '0';

            // Esegui l'operazione OR bitwise
            $result[] = ($bit1 === '1' || $bit2 === '1') ? '1' : '0';
        }

        return $result;
    }

    /**
     * Simulates the logistic map, a classic chaotic system, for a given number of iterations.
     *
     * @param float $r The parameter r that controls the behavior of the logistic map
     * @param float $x0 The initial value of x (between 0 and 1)
     * @param int $iterations The number of iterations to run the map
     * @param float|null $perturbation Optional perturbation added at each iteration
     * @return array The sequence of x values generated by the logistic map
     */
    public function addLogisticMap(float $r, float $x0, int $iterations, ?float $perturbation = null): array
    {
        $x = $x0;
        $result = [];

        for ($i = 0; $i < $iterations; $i++) {
            // Calcola il prossimo valore della mappa logistica
            $x = $r * $x * (1 - $x);

            // Se viene fornita una perturbazione, aggiungila
            if ($perturbation !== null) {
                $x += $perturbation;
                $x = max(0, min(1, $x)); // Mantieni x tra 0 e 1
            }

            // Aggiungi il valore corrente alla lista dei risultati
            $result[] = $x;
        }

        return $result;
    }

    /**
     * Performs addition in a partially ordered set (poset) using the least upper bound (supremum).
     * In this example, the poset is a set of subsets, and the addition is the union of two sets.
     *
     * @param array $set1 The first subset (array of elements)
     * @param array $set2 The second subset (array of elements)
     * @return array The result of the addition (the least upper bound, or union, of the two sets)
     */
    public function addPosetSupremum(array $set1, array $set2): array
    {
        // Calcola l'unione degli insiemi (l'operazione di addizione in questo poset)
        return array_values(array_unique(array_merge($set1, $set2)));
    }

    /**
     * Computes the least upper bound (supremum) of multiple sets in a poset.
     *
     * @param array $sets An array of sets (each set is an array of elements)
     * @return array The least upper bound (union) of all the sets
     */
    public function addMultiplePosetSupremums(array $sets): array
    {
        $result = [];

        foreach ($sets as $set) {
            $result = $this->addPosetSupremum($result, $set);
        }

        return $result;
    }

    /**
     * Adds two Petri nets by combining their places and transitions.
     *
     * @param PetriNet $net1 The first Petri net
     * @param PetriNet $net2 The second Petri net
     * @return PetriNet The resulting Petri net after the addition
     */
    public function addPetriNets(PetriNet $net1, PetriNet $net2): PetriNet
    {
        // Unione dei posti (sommando i token dei posti corrispondenti)
        $combinedPlaces = [];
        foreach ($net1->places as $place => $tokens) {
            $combinedPlaces[$place] = $tokens + ($net2->places[$place] ?? 0);
        }

        // Aggiungere i posti mancanti dalla seconda rete
        foreach ($net2->places as $place => $tokens) {
            if (!isset($combinedPlaces[$place])) {
                $combinedPlaces[$place] = $tokens;
            }
        }

        // Unione delle transizioni
        $combinedTransitions = array_merge($net1->transitions, $net2->transitions);

        // Creazione della rete combinata
        return new PetriNet($combinedPlaces, $combinedTransitions);
    }

    /**
     * Adds a new transition to a Petri net.
     *
     * @param PetriNet $net The Petri net
     * @param array $inputPlaces The input places (places providing tokens)
     * @param array $outputPlaces The output places (places receiving tokens)
     * @return PetriNet The updated Petri net with the new transition
     */
    public function addTransition(PetriNet $net, array $inputPlaces, array $outputPlaces): PetriNet
    {
        // Aggiungere una nuova transizione alla rete
        $net->transitions[] = ['input' => $inputPlaces, 'output' => $outputPlaces];
        return $net;
    }

    /**
     * Performs symbolic addition of two derivatives.
     *
     * @param callable $f The first function f(x)
     * @param callable $g The second function g(x)
     * @param float $x The point at which to compute the derivative
     * @param float $h The step size for numerical differentiation (default: 0.00001)
     * @return float The result of the derivative of f(x) + g(x) at point x
     */
    public function addDerivatives(callable $f, callable $g, float $x, float $h = 0.00001): float
    {
        // Derivata numerica: (f(x+h) - f(x)) / h
        $dfdx = ($f($x + $h) - $f($x)) / $h;
        $dgdx = ($g($x + $h) - $g($x)) / $h;

        // Somma delle derivate
        return $dfdx + $dgdx;
    }

    /**
     * Performs symbolic addition of two integrals over a given interval.
     *
     * @param callable $f The first function f(x)
     * @param callable $g The second function g(x)
     * @param float $a The lower limit of the integral
     * @param float $b The upper limit of the integral
     * @param int $n The number of subdivisions for numerical integration (default: 1000)
     * @return float The result of the integral of f(x) + g(x) over the interval [a, b]
     */
    public function addIntegrals(callable $f, callable $g, float $a, float $b, int $n = 1000): float
    {
        $h = ($b - $a) / $n;
        $sum = 0;

        // Metodo dei trapezi per l'integrazione numerica
        for ($i = 0; $i < $n; $i++) {
            $x0 = $a + $i * $h;
            $x1 = $a + ($i + 1) * $h;
            $fx = ($f($x0) + $f($x1)) / 2;
            $gx = ($g($x0) + $g($x1)) / 2;
            $sum += ($fx + $gx) * $h;
        }

        return $sum;
    }

    /**
     * Adds two vectors in an Euclidean metric space.
     *
     * @param array $vector1 The first vector as an array of numbers
     * @param array $vector2 The second vector as an array of numbers
     * @return array The result of the vector addition
     * @throws \Exception If the vectors do not have the same length
     */
    public function addVectorsInMetricSpace(array $vector1, array $vector2): array
    {
        // Controlla che i vettori abbiano la stessa lunghezza
        if (count($vector1) !== count($vector2)) {
            throw new \Exception('Vectors must have the same length');
        }

        // Somma componente per componente
        $sum = [];
        for ($i = 0; $i < count($vector1); $i++) {
            $sum[] = $this->ath_bcadd($vector1[$i], $vector2[$i]);
        }

        return $sum;
    }

    /**
     * Calculates the Euclidean distance between two vectors in a metric space.
     *
     * @param array $vector1 The first vector as an array of numbers
     * @param array $vector2 The second vector as an array of numbers
     * @return float The Euclidean distance between the two vectors
     * @throws \Exception If the vectors do not have the same length
     */
    public function calculateDistance(array $vector1, array $vector2): float
    {
        // Controlla che i vettori abbiano la stessa lunghezza
        if (count($vector1) !== count($vector2)) {
            throw new \Exception('Vectors must have the same length');
        }

        // Somma i quadrati delle differenze tra le componenti
        $sumOfSquares = 0;
        for ($i = 0; $i < count($vector1); $i++) {
            $diff = $this->ath_bcsub($vector1[$i], $vector2[$i]);
            $square = $this->ath_bcmul($diff, $diff);
            $sumOfSquares = $this->ath_bcadd((string)$sumOfSquares, $square);
        }

        // Restituisci la radice quadrata della somma dei quadrati
        return floatval($this->ath_bcsqrt($sumOfSquares));
    }

    /**
     * Performs the convolution of two discrete probability distributions.
     *
     * @param array $dist1 The first probability distribution as an associative array [value => probability]
     * @param array $dist2 The second probability distribution as an associative array [value => probability]
     * @return array The resulting distribution after convolution
     */
    public function addDistributionsViaConvolution(array $dist1, array $dist2): array
    {
        $result = [];

        // Esegui la convoluzione discreta
        foreach ($dist1 as $x1 => $p1) {
            foreach ($dist2 as $x2 => $p2) {
                $sum = $this->ath_bcadd($x1, $x2);  // Somma dei valori delle variabili casuali
                $prob = $this->ath_bcmul($p1, $p2); // Prodotto delle probabilità

                // Se il valore già esiste, somma le probabilità
                if (isset($result[$sum])) {
                    $result[$sum] = $this->ath_bcadd($result[$sum], $prob);
                } else {
                    $result[$sum] = $prob;
                }
            }
        }

        return $result;
    }

    /**
     * Adds two Dirac delta distributions represented as peaks at specific points.
     *
     * @param array $dirac1 The first Dirac distribution [point => amplitude]
     * @param array $dirac2 The second Dirac distribution [point => amplitude]
     * @return array The result of the addition of Dirac delta distributions
     */
    public function addDiracDistributions(array $dirac1, array $dirac2): array
    {
        $result = $dirac1;

        // Aggiunge i contributi delle delta di Dirac nel secondo array
        foreach ($dirac2 as $point => $amplitude) {
            if (isset($result[$point])) {
                // Somma delle ampiezze in corrispondenza dello stesso punto
                $result[$point] = $this->ath_bcadd($result[$point], $amplitude);
            } else {
                // Aggiunge un nuovo punto se non esiste
                $result[$point] = $amplitude;
            }
        }

        return $result;
    }

    /**
     * Adds two functions using fractional integration of a specified order (Riemann-Liouville).
     *
     * Source: Based on Riemann-Liouville fractional integration definition.
     *
     * @param callable $f The first function to be integrated
     * @param callable $g The second function to be integrated
     * @param float $alpha The order of the fractional integration
     * @param float $x The upper limit of integration
     * @param int $n The number of intervals for numerical integration
     * @return float The result of the fractional integration and summation of f and g
     */
    public function addFractionalIntegrals(callable $f, callable $g, float $alpha, float $x, int $n = 1000): float
    {
        // Passo per l'integrazione numerica
        $h = $x / $n;
        $sum = 0;

        // Funzione Gamma
        $gamma = $this->gammaFunction($alpha);

        // Approssimazione numerica dell'integrale frazionario di Riemann-Liouville
        for ($i = 0; $i <= $n; $i++) {
            $t = $i * $h;
            $kernel = pow($x - $t, $alpha - 1);
            $sum += $kernel * ($f($t) + $g($t)) * $h;
        }

        // Normalizzazione con la funzione Gamma
        return $sum / $gamma;
    }

    /**
     * Adds two functionals in the calculus of variations.
     *
     * Source: Standard form of functional addition in calculus of variations.
     *
     * @param callable $L1 The Lagrangian of the first functional (as a function of x, y(x), y'(x))
     * @param callable $L2 The Lagrangian of the second functional (as a function of x, y(x), y'(x))
     * @param callable $y The function y(x) to be used in the functionals
     * @param callable $dy The derivative y'(x) of the function y(x)
     * @param float $a The lower limit of the integral
     * @param float $b The upper limit of the integral
     * @param int $n The number of intervals for numerical integration
     * @return float The result of the sum of the two functionals
     */
    public function addFunctionals(callable $L1, callable $L2, callable $y, callable $dy, float $a, float $b, int $n = 1000): float
    {
        // Passo per l'integrazione numerica
        $h = ($b - $a) / $n;
        $sum = 0;

        // Approssimazione dell'integrale con il metodo dei trapezi
        for ($i = 0; $i <= $n; $i++) {
            $x = $a + $i * $h;
            $lagrangian1 = $L1($x, $y($x), $dy($x));
            $lagrangian2 = $L2($x, $y($x), $dy($x));

            // Somma delle lagrangiane nei due funzionali
            $sum += ($lagrangian1 + $lagrangian2) * $h;
        }

        return $sum;
    }

    /**
     * Adds two differential operators applied to a function y(x).
     *
     * Source: Based on standard properties of differential operators.
     *
     * @param callable $L1 The first differential operator (function that applies derivatives)
     * @param callable $L2 The second differential operator (function that applies derivatives)
     * @param callable $y The function y(x) to be differentiated
     * @param float $x The point at which to evaluate the operators
     * @param float $h The step size for numerical differentiation (default: 0.00001)
     * @return float The result of the sum of the differential operators applied to y(x)
     */
    public function addDifferentialOperators(callable $L1, callable $L2, callable $y, float $x, float $h = 0.00001): float
    {
        // Applica il primo operatore differenziale a y(x)
        $L1_result = $L1($y, $x, $h);

        // Applica il secondo operatore differenziale a y(x)
        $L2_result = $L2($y, $x, $h);

        // Somma i risultati
        return floatval($this->ath_bcadd($L1_result, $L2_result));
    }

    /**
     * Example of a first-order differential operator.
     *
     * Source: Standard first-order differentiation.
     *
     * @param callable $y The function y(x) to be differentiated
     * @param float $x The point at which to evaluate the derivative
     * @param float $h The step size for numerical differentiation
     * @return float The result of the first derivative at x
     */
    public function firstOrderOperator(callable $y, float $x, float $h): float
    {
        return ($y($x + $h) - $y($x)) / $h;
    }

    /**
     * Example of a second-order differential operator.
     *
     * Source: Standard second-order differentiation.
     *
     * @param callable $y The function y(x) to be differentiated
     * @param float $x The point at which to evaluate the second derivative
     * @param float $h The step size for numerical differentiation
     * @return float The result of the second derivative at x
     */
    public function secondOrderOperator(callable $y, float $x, float $h): float
    {
        return ($y($x + $h) - 2 * $y($x) + $y($x - $h)) / ($h * $h);
    }

    /**
     * Adds a sequence of time-dependent functions or operators using chronological ordering.
     *
     * Source: Based on standard time-ordering (T-ordering) operator used in quantum mechanics.
     *
     * @param array $functions An array of [time => function] pairs
     * @return array The time-ordered array of functions
     */
    public function addChronologically(array $functions): array
    {
        // Ordinamento cronologico delle funzioni in base al tempo
        ksort($functions);

        // Somma delle funzioni ordinate temporalmente
        $result = [];
        foreach ($functions as $time => $function) {
            $result[$time] = $function;
        }

        return $result;
    }

    /**
     * Evaluates the sum of time-ordered functions at a specific time.
     *
     * Source: Chronological ordering applied to a sequence of functions.
     *
     * @param array $functions An array of [time => function] pairs
     * @param float $time The time at which to evaluate the sum
     * @return float The sum of the functions evaluated at the given time
     */
    public function evaluateChronologicalSum(array $functions, float $time): float
    {
        // Ordina cronologicamente le funzioni
        $timeOrderedFunctions = $this->addChronologically($functions);

        // Somma le funzioni in base al tempo fornito
        $sum = 0;
        foreach ($timeOrderedFunctions as $t => $function) {
            if ($t <= $time) {
                $sum = $this->ath_bcadd((string)$sum, $function($time));
            }
        }

        return floatval($sum);
    }

    /**
     * Adds partial derivatives of a function with respect to different variables.
     *
     * Source: Standard numerical differentiation for partial derivatives.
     *
     * @param callable $f The function f(x, y, z, ...) to be differentiated
     * @param array $vars The variables at which to evaluate the function (as an associative array: ['x' => value, 'y' => value, ...])
     * @param array $partialVars The variables with respect to which to take partial derivatives
     * @param float $h The step size for numerical differentiation (default: 0.00001)
     * @return float The sum of the partial derivatives
     */
    public function addPartialDerivatives(callable $f, array $vars, array $partialVars, float $h = 0.00001): float
    {
        $sum = 0;

        // Somma delle derivate parziali rispetto alle variabili specificate
        foreach ($partialVars as $var) {
            $sum = $this->ath_bcadd((string)$sum, (string)$this->partialDerivative($f, $vars, $var, $h));
        }

        return floatval($sum);
    }

    /**
     * Calculates the partial derivative of a function with respect to a given variable using numerical differentiation.
     *
     * Source: Standard numerical differentiation for partial derivatives.
     *
     * @param callable $f The function f(x, y, z, ...) to be differentiated
     * @param array $vars The variables at which to evaluate the function (as an associative array: ['x' => value, 'y' => value, ...])
     * @param string $var The variable with respect to which the partial derivative is taken
     * @param float $h The step size for numerical differentiation
     * @return float The partial derivative with respect to the variable
     */
    public function partialDerivative(callable $f, array $vars, string $var, float $h): float
    {
        // Calcola f(x + h, y, z, ...) e f(x - h, y, z, ...)
        $varsForward = $vars;
        $varsForward[$var] += $h;

        $varsBackward = $vars;
        $varsBackward[$var] -= $h;

        // Differenziazione numerica per la derivata parziale
        return ($f($varsForward) - $f($varsBackward)) / (2 * $h);
    }

    /**
     * Adds two functions or signals using convolution.
     *
     * Source: Based on standard discrete convolution operation.
     *
     * @param array $f The first discrete function or signal as an array
     * @param array $g The second discrete function or signal as an array
     * @return array The result of the convolution of f and g
     */
    public function addConvolutions(array $f, array $g): array
    {
        $n = count($f);
        $m = count($g);
        $result = array_fill(0, $n + $m - 1, 0);

        // Esegui la convoluzione discreta
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $result[$i + $j] = $this->ath_bcadd($result[$i + $j], $this->ath_bcmul($f[$i], $g[$j]));
            }
        }

        return $result;
    }

    /**
     * Adds different modes of oscillation of a string in string theory.
     *
     * Source: Based on the sum of harmonic modes in string theory.
     *
     * @param array $modes An array of harmonic functions representing different modes of oscillation
     * @param float $t The time at which to evaluate the sum of oscillations
     * @return float The sum of the oscillations at time t
     */
    public function addStringModes(array $modes, float $t): float
    {
        $sum = 0;

        // Somma delle oscillazioni delle varie modalità
        foreach ($modes as $mode) {
            $sum = $this->ath_bcadd((string)$sum, $mode($t));
        }

        return floatval($sum);
    }

    /**
     * Adds two Dirac operators, represented as 4x4 matrices.
     *
     * Source: Based on standard matrix addition for Dirac operators in quantum mechanics.
     *
     * @param array $diracOperator1 The first Dirac operator as a 4x4 matrix
     * @param array $diracOperator2 The second Dirac operator as a 4x4 matrix
     * @return array The sum of the two Dirac operators
     * @throws \Exception If the matrices are not 4x4
     */
    public function addDiracOperators(array $diracOperator1, array $diracOperator2): array
    {
        // Verifica che entrambe le matrici siano 4x4
        if (count($diracOperator1) !== 4 || count($diracOperator2) !== 4) {
            throw new \Exception('Dirac operators must be 4x4 matrices');
        }
        for ($i = 0; $i < 4; $i++) {
            if (count($diracOperator1[$i]) !== 4 || count($diracOperator2[$i]) !== 4) {
                throw new \Exception('Dirac operators must be 4x4 matrices');
            }
        }

        // Somma gli operatori di Dirac (matrici)
        $result = [];
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                $result[$i][$j] = $this->ath_bcadd($diracOperator1[$i][$j], $diracOperator2[$i][$j]);
            }
        }

        return $result;
    }

    /**
     * Adds two Heisenberg operators, represented as matrices, at a given time t.
     *
     * Source: Based on matrix addition for Heisenberg operators in quantum mechanics.
     *
     * @param array $operator1 The first Heisenberg operator as a matrix
     * @param array $operator2 The second Heisenberg operator as a matrix
     * @return array The sum of the two operators
     * @throws \Exception If the matrices are not square or have different dimensions
     */
    public function addHeisenbergOperators(array $operator1, array $operator2): array
    {
        // Verifica che entrambe le matrici siano della stessa dimensione
        $n = count($operator1);
        if ($n !== count($operator2) || $n === 0) {
            throw new \Exception('Heisenberg operators must have the same dimensions and be non-empty.');
        }

        for ($i = 0; $i < $n; $i++) {
            if (count($operator1[$i]) !== $n || count($operator2[$i]) !== $n) {
                throw new \Exception('Heisenberg operators must be square matrices of the same size.');
            }
        }

        // Somma gli operatori (matrici)
        $result = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $result[$i][$j] = $this->ath_bcadd($operator1[$i][$j], $operator2[$i][$j]);
            }
        }

        return $result;
    }

    /**
     * Adds two brane surfaces in string theory, represented as multidimensional arrays.
     *
     * Source: Based on the sum of brane surfaces in string theory models.
     *
     * @param array $brane1 The first brane surface as a multidimensional array
     * @param array $brane2 The second brane surface as a multidimensional array
     * @return array The resulting brane surface after the addition
     * @throws \Exception If the brane surfaces do not have the same dimensions
     */
    public function addBraneSurfaces(array $brane1, array $brane2): array
    {
        // Verifica che le superfici delle brane abbiano le stesse dimensioni
        if (!$this->checkSameDimensions($brane1, $brane2)) {
            throw new \Exception('Brane surfaces must have the same dimensions');
        }

        // Somma le superfici delle brane componente per componente
        return $this->addBranesRecursively($brane1, $brane2);
    }

    /**
     * Adds the interactions between a particle (spin) and the mean field in a mean field model.
     *
     * Source: Based on the mean field theory for the Ising model.
     *
     * @param array $spins The array of spins (-1 or 1) representing the system
     * @param float $interactionStrength The interaction strength between spins
     * @param float $externalField The external magnetic field applied to the system
     * @return float The total energy due to the interactions between spins and the mean field
     */
    public function addMeanFieldInteractions(array $spins, float $interactionStrength, float $externalField): float
    {
        $meanField = $this->calculateMeanField($spins);
        $totalEnergy = 0;

        // Calcola l'energia totale sommando le interazioni tra ciascun spin e il campo medio
        foreach ($spins as $spin) {
            $interactionEnergy = $interactionStrength * $spin * $meanField;
            $externalEnergy = $spin * $externalField;
            $totalEnergy = $this->ath_bcadd(
                (string)$totalEnergy,
                $this->ath_bcadd(
                    (string)$interactionEnergy,
                    (string)$externalEnergy
                )
            );
        }

        return floatval($totalEnergy);
    }

    /**
     * Adds the probabilities of two events.
     *
     * Source: Based on the standard rules of probability addition.
     *
     * @param float $probA The probability of event A
     * @param float $probB The probability of event B
     * @param ?float $probIntersection The probability of both A and B occurring (for non-mutually exclusive events)
     * @return float The result of the probability addition
     */
    public function addProbabilities(float $probA, float $probB, ?float $probIntersection = null): float
    {
        if ($probIntersection === null) {
            // Eventi mutuamente esclusivi
            return floatval($this->ath_bcadd((string)$probA, (string)$probB));
        } else {
            // Eventi non mutuamente esclusivi
            $sum = $this->ath_bcadd((string)$probA, (string)$probB);
            return floatval($this->ath_bcsub((string)$sum, (string)$probIntersection));
        }
    }

    /**
     * Adds the probabilities of multiple events, adjusting for non-mutually exclusive events.
     *
     * Source: Based on the rules of probability addition for multiple events.
     *
     * @param array $events An array of probabilities of the events
     * @param ?array $intersections An array of probabilities of intersections of the events (optional)
     * @return float The total probability of at least one event occurring
     */
    public function addEventProbabilities(array $events, ?array $intersections = null): float
    {
        // Somma delle probabilità degli eventi individuali
        $totalProbability = array_reduce($events, function ($carry, $prob) {
            return $this->ath_bcadd((string)$carry, $prob);
        }, 0);

        // Se ci sono intersezioni, sottrarle dalla somma totale
        if ($intersections !== null) {
            foreach ($intersections as $intersection) {
                $totalProbability = $this->ath_bcsub((string)$totalProbability, $intersection);
            }
        }

        return floatval($totalProbability);
    }

    /**
     * Adds multiple random walks together to create an aggregate path.
     *
     * Source: Based on the standard random walk model in stochastic processes.
     *
     * @param array $randomWalks An array of random walks, where each walk is an array of positions
     * @return array The aggregated random walk, summing the positions at each step
     */
    public function addRandomWalks(array $randomWalks): array
    {
        $numSteps = count($randomWalks[0]);
        $aggregatedWalk = array_fill(0, $numSteps, 0);

        // Somma dei cammini casuali passo per passo
        foreach ($randomWalks as $walk) {
            for ($i = 0; $i < $numSteps; $i++) {
                $aggregatedWalk[$i] = $this->ath_bcadd($aggregatedWalk[$i], $walk[$i]);
            }
        }

        return $aggregatedWalk;
    }

    /**
     * Generates a random walk with a given number of steps.
     *
     * Source: Standard random walk generator with steps +1 or -1.
     *
     * @param int $numSteps The number of steps in the random walk
     * @return array The generated random walk
     */
    public function generateRandomWalk(int $numSteps): array
    {
        $randomWalk = [0]; // Partenza da 0
        for ($i = 1; $i < $numSteps; $i++) {
            $randomWalk[] = $randomWalk[$i - 1] + (rand(0, 1) ? 1 : -1);
        }
        return $randomWalk;
    }

    /**
     * Simulates the stochastic integration for the evolution of an asset price using the Black-Scholes model.
     *
     * Source: Based on the stochastic integration for Black-Scholes SDE.
     *
     * @param float $initialPrice The initial price of the asset
     * @param float $mu The drift (expected growth rate)
     * @param float $sigma The volatility of the asset
     * @param float $timeStep The time step for the simulation (e.g., 1/252 for daily steps)
     * @param int $numSteps The number of steps in the simulation
     * @return array The simulated asset prices over time
     */
    public function integrateStochastically(float $initialPrice, float $mu, float $sigma, float $timeStep, int $numSteps): array
    {
        $prices = [$initialPrice];
        $currentPrice = $initialPrice;

        for ($i = 1; $i <= $numSteps; $i++) {
            // Genera un incremento casuale basato su un moto browniano standard
            $brownianIncrement = $this->generateBrownianIncrement($timeStep);

            // Applica la formula di Black-Scholes: dS(t) = mu * S(t) * dt + sigma * S(t) * dW(t)
            $drift = $mu * $currentPrice * $timeStep;
            $diffusion = $sigma * $currentPrice * $brownianIncrement;

            // Aggiorna il prezzo corrente dell'asset
            $currentPrice += $drift + $diffusion;
            $prices[] = $currentPrice;
        }

        return $prices;
    }

    /**
     * Generates a random increment for the Brownian motion.
     *
     * Source: Based on the properties of standard Brownian motion (normal distribution).
     *
     * @param float $timeStep The time step for the Brownian increment
     * @return float The generated Brownian increment
     */
    private function generateBrownianIncrement(float $timeStep): float
    {
        // Usa una distribuzione normale standard con media 0 e varianza sqrt(timeStep)
        return sqrt($timeStep) * rand() / getrandmax();
    }

    /**
     * Adds the probabilities of states in a Markov chain after multiple transitions.
     *
     * Source: Based on the Markov chain model and matrix multiplication for state transitions.
     *
     * @param array $initialState The initial distribution of states (vector of probabilities)
     * @param array $transitionMatrix The matrix of transition probabilities
     * @param int $numSteps The number of transitions (steps) in the Markov chain
     * @return array The resulting distribution of states after the specified number of steps
     */
    public function addMarkovStates(array $initialState, array $transitionMatrix, int $numSteps): array
    {
        $currentState = $initialState;

        // Moltiplica la matrice di transizione per il vettore degli stati ad ogni passo
        for ($step = 0; $step < $numSteps; $step++) {
            $currentState = $this->multiplyMatrixVector($transitionMatrix, $currentState);
        }

        return $currentState;
    }

    /**
     * Adds the components of two multivariate normal distributions.
     *
     * Source: Based on the standard properties of multivariate normal distributions.
     *
     * @param array $mean1 The mean vector of the first distribution
     * @param array $cov1 The covariance matrix of the first distribution
     * @param array $mean2 The mean vector of the second distribution
     * @param array $cov2 The covariance matrix of the second distribution
     * @return array The resulting mean vector and covariance matrix of the summed distribution
     */
    public function addMultivariateNormals(array $mean1, array $cov1, array $mean2, array $cov2): array
    {
        // Somma delle medie vettoriali
        $summedMean = [];
        for ($i = 0; $i < count($mean1); $i++) {
            $summedMean[] = $this->ath_bcadd($mean1[$i], $mean2[$i]);
        }

        // Somma delle matrici di covarianza
        $summedCov = [];
        for ($i = 0; $i < count($cov1); $i++) {
            $summedCov[$i] = [];
            for ($j = 0; $j < count($cov1[$i]); $j++) {
                $summedCov[$i][$j] = $this->ath_bcadd($cov1[$i][$j], $cov2[$i][$j]);
            }
        }

        return [
            'mean' => $summedMean,
            'covariance' => $summedCov
        ];
    }

    /**
     * Unites two graphs by combining their nodes and edges.
     *
     * Source: Based on standard graph union operation in graph theory.
     *
     * @param array $graph1 The adjacency list of the first graph
     * @param array $graph2 The adjacency list of the second graph
     * @return array The adjacency list of the union of the two graphs
     */
    public function addGraphUnion(array $graph1, array $graph2): array
    {
        $unionGraph = $graph1;

        // Aggiungi i nodi e gli archi di graph2 al grafo unione
        foreach ($graph2 as $node => $neighbors) {
            if (!isset($unionGraph[$node])) {
                $unionGraph[$node] = [];
            }
            foreach ($neighbors as $neighbor) {
                if (!in_array($neighbor, $unionGraph[$node])) {
                    $unionGraph[$node][] = $neighbor;
                }
            }
        }

        return $unionGraph;
    }

    /**
     * Sums the weights of edges between two nodes in a weighted graph.
     *
     * Source: Based on weighted graph operations in graph theory.
     *
     * @param array $graph The adjacency list of the graph with weights (node => [neighbor => weight])
     * @param string $node1 The first node
     * @param string $node2 The second node
     * @return float The total weight of the edge between node1 and node2
     */
    public function addWeightedEdges(array $graph, string $node1, string $node2): float
    {
        $weightSum = '0';

        // Somma i pesi degli archi tra node1 e node2 se esistono
        if (isset($graph[$node1][$node2])) {
            $weightSum = $this->ath_bcadd($weightSum, $graph[$node1][$node2]);
        }

        if (isset($graph[$node2][$node1])) {
            $weightSum = $this->ath_bcadd($weightSum, $graph[$node2][$node1]);
        }

        return floatval($weightSum);
    }

    /**
     * Combines two DFAs (Deterministic Finite Automata) by performing a union operation.
     *
     * Source: Based on the union operation in automata theory.
     *
     * @param array $dfa1 The transition table of the first DFA
     * @param array $dfa2 The transition table of the second DFA
     * @param string $initialState1 The initial state of the first DFA
     * @param string $initialState2 The initial state of the second DFA
     * @param array $acceptStates1 The accept states of the first DFA
     * @param array $acceptStates2 The accept states of the second DFA
     * @return array The transition table and states of the union of the two DFAs
     */
    public function addAutomataUnion(array $dfa1, array $dfa2, string $initialState1, string $initialState2, array $acceptStates1, array $acceptStates2): array
    {
        $unionAutomaton = [];
        $combinedStates = [];

        // Prodotto cartesiano degli stati
        foreach ($dfa1 as $state1 => $transitions1) {
            foreach ($dfa2 as $state2 => $transitions2) {
                $combinedState = $state1 . ',' . $state2;
                $combinedStates[$combinedState] = [];
                foreach ($transitions1 as $symbol => $nextState1) {
                    $nextState2 = $dfa2[$state2][$symbol];
                    $combinedStates[$combinedState][$symbol] = $nextState1 . ',' . $nextState2;
                }
            }
        }

        // Determina gli stati iniziali e finali
        $initialState = $initialState1 . ',' . $initialState2;
        $acceptStates = [];
        foreach ($acceptStates1 as $state1) {
            foreach ($acceptStates2 as $state2) {
                $acceptStates[] = $state1 . ',' . $state2;
            }
        }

        return [
            'states' => $combinedStates,
            'initial_state' => $initialState,
            'accept_states' => $acceptStates
        ];
    }

    /**
     * Sums the weights of connections between two disjoint sets of vertices in a bipartite graph.
     *
     * Source: Based on operations in bipartite graph theory.
     *
     * @param array $bipartiteGraph The adjacency matrix of the bipartite graph (with weights)
     * @param array $setU The first set of vertices (U)
     * @param array $setV The second set of vertices (V)
     * @return float The total sum of weights between set U and set V
     */
    public function addBipartiteConnections(array $bipartiteGraph, array $setU, array $setV): float
    {
        $totalWeight = '0';

        // Somma i pesi degli archi tra i vertici di U e V
        foreach ($setU as $u) {
            foreach ($setV as $v) {
                if (isset($bipartiteGraph[$u][$v])) {
                    $totalWeight = $this->ath_bcadd($totalWeight, $bipartiteGraph[$u][$v]);
                }
            }
        }

        return floatval($totalWeight);
    }

    /**
     * Sums the weights of a Hamiltonian path in a weighted graph.
     *
     * Source: Based on Hamiltonian path theory in graph theory.
     *
     * @param array $graph The adjacency matrix of the graph with weights
     * @param array $path The Hamiltonian path (ordered list of vertices)
     * @return float The total weight of the Hamiltonian path
     */
    public function addHamiltonianPath(array $graph, array $path): float
    {
        $totalWeight = '0';

        // Somma i pesi degli archi lungo il cammino hamiltoniano
        for ($i = 0; $i < count($path) - 1; $i++) {
            $u = $path[$i];
            $v = $path[$i + 1];

            if (isset($graph[$u][$v])) {
                $totalWeight = $this->ath_bcadd($totalWeight, $graph[$u][$v]);
            } else {
                // Se non c'è un arco tra i due vertici, non è un cammino valido
                throw new \Exception("No edge exists between $u and $v in the given graph.");
            }
        }

        return floatval($totalWeight);
    }

    /**
     * Simulates the computation in a single neuron, including the addition of weighted inputs and bias.
     *
     * Source: Standard neuron model in artificial neural networks.
     *
     * @param array $inputs The input vector
     * @param array $weights The weight vector corresponding to the inputs
     * @param float $bias The bias term
     * @param string $activation The activation function to apply ('sigmoid' or 'relu')
     * @return float The output of the neuron after applying the activation function
     */
    public function addWithNeuron(array $inputs, array $weights, float $bias, string $activation = 'sigmoid'): float
    {
        // Somma pesata degli input più bias
        $sum = '0';
        for ($i = 0; $i < count($inputs); $i++) {
            $sum = $this->ath_bcadd($sum, $this->ath_bcmul($inputs[$i], $weights[$i]));
        }
        $sum = $this->ath_bcadd($sum, (string)$bias);

        // Applica la funzione di attivazione
        switch ($activation) {
            case 'relu':
                return max(0, $sum);
            case 'sigmoid':
            default:
                return $this->sigmoid(floatval($sum));
        }
    }

    /**
     * Simulates the update of a Hopfield network for a given number of steps.
     *
     * Source: Based on the Hopfield network model.
     *
     * @param array $initialState The initial state vector of the network
     * @param array $weights The weight matrix representing the connections between neurons
     * @param int $numSteps The number of update steps to perform
     * @return array The final state of the network after the specified number of steps
     */
    public function addHopfieldNetwork(array $initialState, array $weights, int $numSteps): array
    {
        $state = $initialState;

        // Esegui aggiornamenti iterativi per il numero di passi specificato
        for ($step = 0; $step < $numSteps; $step++) {
            $state = $this->updateHopfieldState($state, $weights);
        }

        return $state;
    }

    /**
     * Updates the weights of a neural network layer using gradient descent.
     *
     * Source: Based on the gradient descent algorithm in deep learning.
     *
     * @param array $weights The current weights of the layer
     * @param array $gradients The gradients of the loss function with respect to the weights
     * @param float $learningRate The learning rate for gradient descent
     * @return array The updated weights after applying the gradient descent step
     */
    public function updateWeights(array $weights, array $gradients, float $learningRate): array
    {
        $updatedWeights = [];

        // Somma pesata dei gradienti e aggiornamento dei pesi
        for ($i = 0; $i < count($weights); $i++) {
            $gradientUpdate = $this->ath_bcmul($gradients[$i], (string)$learningRate);
            $updatedWeights[] = $this->ath_bcsub($weights[$i], $gradientUpdate);
        }

        return $updatedWeights;
    }

    /**
     * Calculates the gradient of the loss function with respect to the weights.
     *
     * Source: Based on backpropagation in neural networks.
     *
     * @param array $inputs The input to the layer
     * @param array $errors The error terms from the output layer
     * @return array The gradients of the loss function with respect to the weights
     */
    public function calculateGradients(array $inputs, array $errors): array
    {
        $gradients = [];

        // Calcolo del gradiente per ciascun peso
        for ($i = 0; $i < count($inputs); $i++) {
            $gradients[] = $this->ath_bcmul($inputs[$i], $errors[$i]);
        }

        return $gradients;
    }

    /**
     * Calculates the probability of an observation under a Gaussian Mixture Model (GMM).
     *
     * Source: Based on the mixture of Gaussians model in generative modeling.
     *
     * @param array $x The observation (input vector)
     * @param array $means An array of mean vectors for each Gaussian component
     * @param array $covariances An array of covariance matrices for each Gaussian component
     * @param array $weights The weights for each Gaussian component
     * @return float The probability of the observation under the GMM
     */
    public function addGaussianMixture(array $x, array $means, array $covariances, array $weights): float
    {
        $probability = '0';

        // Somma delle probabilità pesate di ciascuna componente gaussiana
        for ($i = 0; $i < count($weights); $i++) {
            $componentProb = $this->gaussianProbability($x, $means[$i], $covariances[$i]);
            $weightedProb = $this->ath_bcmul($weights[$i], (string)$componentProb);
            $probability = $this->ath_bcadd($probability, $weightedProb);
        }

        return floatval($probability);
    }

    /**
     * Performs the convolution of two sequences and sums the result.
     *
     * Source: Based on the convolution operation in signal processing.
     *
     * @param array $sequence1 The first input sequence
     * @param array $sequence2 The second input sequence (kernel)
     * @return array The result of the convolution
     */
    public function addConvolution(array $sequence1, array $sequence2): array
    {
        $convolutionResult = [];
        $len1 = count($sequence1);
        $len2 = count($sequence2);
        $totalLength = $len1 + $len2 - 1;

        // Effettua la convoluzione discreta tra sequence1 e sequence2
        for ($n = 0; $n < $totalLength; $n++) {
            $sum = '0';
            for ($m = 0; $m < $len2; $m++) {
                if (($n - $m >= 0) && ($n - $m < $len1)) {
                    $sum = $this->ath_bcadd($sum, $this->ath_bcmul($sequence1[$n - $m], $sequence2[$m]));
                }
            }
            $convolutionResult[] = $sum;
        }

        return $convolutionResult;
    }

    /**
     * Sums two wavelet-transformed signals by adding their components at each scale.
     *
     * Source: Based on the discrete wavelet transform (DWT) in signal processing.
     *
     * @param array $waveletCoefficients1 The wavelet coefficients of the first signal
     * @param array $waveletCoefficients2 The wavelet coefficients of the second signal
     * @return array The wavelet coefficients of the sum of the two signals
     */
    public function addWaveletTransform(array $waveletCoefficients1, array $waveletCoefficients2): array
    {
        $sumCoefficients = [];
        $numScales = max(count($waveletCoefficients1), count($waveletCoefficients2));

        // Somma i coefficienti wavelet per ogni scala
        for ($scale = 0; $scale < $numScales; $scale++) {
            $scaleCoeffs1 = isset($waveletCoefficients1[$scale]) ? $waveletCoefficients1[$scale] : [];
            $scaleCoeffs2 = isset($waveletCoefficients2[$scale]) ? $waveletCoefficients2[$scale] : [];

            $scaleSum = [];
            $numCoeffs = max(count($scaleCoeffs1), count($scaleCoeffs2));

            for ($i = 0; $i < $numCoeffs; $i++) {
                $coeff1 = isset($scaleCoeffs1[$i]) ? $scaleCoeffs1[$i] : '0';
                $coeff2 = isset($scaleCoeffs2[$i]) ? $scaleCoeffs2[$i] : '0';

                // Somma dei coefficienti a ogni livello di scala
                $scaleSum[] = $this->ath_bcadd($coeff1, $coeff2);
            }

            $sumCoefficients[$scale] = $scaleSum;
        }

        return $sumCoefficients;
    }

    /**
     * Reconstructs a signal from its wavelet coefficients.
     *
     * Source: Based on inverse discrete wavelet transform (IDWT) theory.
     *
     * @param array $waveletCoefficients The wavelet coefficients of the signal
     * @return array The reconstructed signal
     */
    public function inverseWaveletTransform(array $waveletCoefficients): array
    {
        // Implementazione semplificata di una ricostruzione del segnale
        $reconstructedSignal = [];
        foreach ($waveletCoefficients as $scaleCoeffs) {
            foreach ($scaleCoeffs as $coeff) {
                $reconstructedSignal[] = floatval($coeff);  // Trasforma i coefficienti in valori
            }
        }

        return $reconstructedSignal;
    }

    /**
     * Adds two functions in the Laplace domain by summing their Laplace transforms.
     *
     * Source: Based on the linearity property of the Laplace transform.
     *
     * @param array $laplaceTransform1 The Laplace transform of the first function
     * @param array $laplaceTransform2 The Laplace transform of the second function
     * @return array The Laplace transform of the sum of the two functions
     */
    public function addLaplaceTransforms(array $laplaceTransform1, array $laplaceTransform2): array
    {
        $sumTransform = [];

        // Somma i termini della trasformata di Laplace
        foreach ($laplaceTransform1 as $term1) {
            foreach ($laplaceTransform2 as $term2) {
                $sumTransform[] = $this->ath_bcadd($term1, $term2);
            }
        }

        return $sumTransform;
    }

    /**
     * Calculates the inverse Laplace transform of a function.
     *
     * Source: Based on standard inverse Laplace transform techniques.
     *
     * @param array $laplaceTransform The Laplace transform of the function
     * @return array The function in the time domain
     */
    public function inverseLaplaceTransform(array $laplaceTransform): array
    {
        // Implementazione semplificata della trasformata inversa di Laplace
        $timeDomainFunction = [];
        foreach ($laplaceTransform as $term) {
            // Approssimiamo il termine inverso come una funzione temporale esponenziale
            $timeDomainFunction[] = $this->approximateInverse($term);
        }

        return $timeDomainFunction;
    }

    /**
     * Sums the terms of a perturbation series up to a given order.
     *
     * Source: Based on the perturbation theory approach in physics and mathematics.
     *
     * @param array $terms The terms of the perturbation series (each element corresponds to a term)
     * @param string $epsilon The perturbation parameter
     * @param int $order The order up to which the perturbation terms should be summed
     * @return string The sum of the perturbation series up to the specified order
     */
    public function addPerturbationSeries(array $terms, string $epsilon, int $order): string
    {
        $sum = '0';

        // Somma i termini fino all'ordine specificato
        for ($n = 0; $n <= $order; $n++) {
            if (isset($terms[$n])) {
                $term = $this->ath_bcmul($terms[$n], $this->ath_bcpow($epsilon, (string)$n));
                $sum = $this->ath_bcadd($sum, $term);
            }
        }

        return $sum;
    }

    /**
     * Sums the terms of a quantum perturbation series for energy corrections.
     *
     * Source: Based on perturbation theory in quantum mechanics.
     *
     * @param array $terms The terms of the perturbation series (each term corresponds to a correction order)
     * @param string $lambda The perturbation parameter
     * @param int $order The order up to which the perturbation terms should be summed
     * @return string The total energy correction up to the specified order
     */
    public function addQuantumPerturbationSeries(array $terms, string $lambda, int $order): string
    {
        $totalEnergy = '0';

        // Somma i termini fino all'ordine specificato
        for ($n = 0; $n <= $order; $n++) {
            if (isset($terms[$n])) {
                // Moltiplica il termine perturbativo per la potenza corrispondente di lambda
                $term = $this->ath_bcmul($terms[$n], $this->ath_bcpow($lambda, (string)$n));
                $totalEnergy = $this->ath_bcadd($totalEnergy, $term);
            }
        }

        return $totalEnergy;
    }

    /**
     * Sums the components of the Einstein field equations.
     *
     * Source: Based on the formalism of Einstein's field equations in general relativity.
     *
     * @param array $einsteinTensor The components of the Einstein tensor G_{\mu\nu}
     * @param array $metricTensor The components of the metric tensor g_{\mu\nu}
     * @param array $energyMomentumTensor The components of the energy-momentum tensor T_{\mu\nu}
     * @param string $cosmologicalConstant The value of the cosmological constant Λ
     * @return array The sum of the tensors representing the Einstein field equations
     */
    public function addEinsteinFieldEquations(
        array $einsteinTensor,
        array $metricTensor,
        array $energyMomentumTensor,
        string $cosmologicalConstant
    ): array {
        $fieldEquations = [];
        $constantFactor = $this->ath_bcmul('8 * pi * G', '1 / c^4');

        // Somma G_{\mu\nu} + Λ g_{\mu\nu} - (8 π G / c^4) T_{\mu\nu}
        foreach ($einsteinTensor as $index => $g_mu_nu) {
            $lambdaTerm = $this->ath_bcmul($cosmologicalConstant, $metricTensor[$index]);
            $t_mu_nu = $this->ath_bcmul($constantFactor, $energyMomentumTensor[$index]);

            // Somma i contributi per ogni componente tensoriale
            $fieldEquations[$index] = $this->ath_bcadd($g_mu_nu, $this->ath_bcsub($lambdaTerm, $t_mu_nu));
        }

        return $fieldEquations;
    }








    /**
     * Helper function to add two large numbers represented as strings with carry-over management.
     * This function simulates the addition with carry, ensuring the carry is propagated correctly.
     *
     * @param string $num1 The first number as a string
     * @param string $num2 The second number as a string
     * @return string The result of the addition with carry as a string
     */
    private function addStringsWithCarry(string $num1, string $num2): string
    {
        // Use the existing addStrings helper method from ATHMath to perform addition with carry management
        $maxLength = max(strlen($num1), strlen($num2));
        $num1 = str_pad($num1, $maxLength, '0', STR_PAD_LEFT);
        $num2 = str_pad($num2, $maxLength, '0', STR_PAD_LEFT);

        $carry = 0;
        $result = '';

        // Loop through the digits from right to left, adding them and managing the carry
        for ($i = $maxLength - 1; $i >= 0; $i--) {
            $sum = (int)$num1[$i] + (int)$num2[$i] + $carry;
            $carry = (int)($sum / 10); // Calculate carry
            $result = ($sum % 10) . $result; // Append the current digit to the result
        }

        // If there's a remaining carry, add it to the result
        if ($carry > 0) {
            $result = $carry . $result;
        }

        return $result;
    }

    /**
     * Helper function to calculate the greatest common divisor (GCD) of two numbers using the Euclidean algorithm.
     *
     * @param string $a First number as a string
     * @param string $b Second number as a string
     * @return string The GCD of the two numbers as a string
     */
    private function gcd(string $a, string $b): string
    {
        while ($b !== '0') {
            $remainder = $this->mod($a, $b);
            $a = $b;
            $b = $remainder;
        }
        return $a;
    }

    /**
     * Helper function to approximate an irrational number like sqrt(2), pi, e.
     * This function could be extended to handle various irrational numbers.
     *
     * @param string $irrational The symbol representing the irrational number (e.g., "pi", "sqrt2")
     * @param ?int $scale Optional number of decimal places for the approximation
     * @return string The approximation of the irrational number as a string
     */
    public function approximateIrrational(string $irrational, ?int $scale = null): string
    {
        // Default approximations for common irrational numbers
        $irrationalMap = [
            'pi'    => '3.14159265358979323846',
            'sqrt2' => '1.41421356237309504880',
            'e'     => '2.71828182845904523536'
        ];

        // Get the approximation of the number from the map
        $approximation = $irrationalMap[$irrational] ?? '0';

        // Adjust the scale if provided
        if ($scale !== null) {
            return $this->adjustScale($approximation, $scale);
        }

        return $approximation;
    }

    /**
     * Converts a number from base n to base 10 using custom math functions.
     *
     * @param string $num The number in base n as a string
     * @param int $base The base in which the number is represented
     * @return string The number converted to base 10 as a string
     */
    private function baseToDecimal(string $num, int $base): string
    {
        $decimal_value = '0';
        $length = strlen($num);

        // Loop through each digit of the number from left to right
        for ($i = 0; $i < $length; $i++) {
            // Convert the current digit to base 10
            $digit_value = base_convert($num[$i], $base, 10);

            // Multiply the digit by the base raised to the appropriate power using 'ath_bcpow' and 'ath_bcmul'
            $power = $length - $i - 1;
            $term_value = $this->ath_bcmul($digit_value, $this->ath_bcpow((string)$base, (string)$power));

            // Add the term value to the total decimal value using 'ath_bcadd'
            $decimal_value = $this->ath_bcadd($decimal_value, $term_value);
        }

        return $decimal_value;
    }

    /**
     * Converts a number from base 10 to base n using custom math functions.
     *
     * @param string $num The number in base 10 as a string
     * @param int $base The base to which the number will be converted
     * @return string The number converted to base n as a string
     */
    private function decimalToBase(string $num, int $base): string
    {
        $result = '';

        // Continue dividing the number by the base until the quotient is zero
        while ($this->ath_bccomp($num, '0') > 0) {
            // Get the remainder of the division using 'ath_bcmod'
            $remainder = $this->ath_bcmod($num, (string)$base);

            // Convert the remainder to the appropriate digit (for bases > 10)
            $result = base_convert($remainder, 10, $base) . $result;

            // Divide the number by the base for the next iteration using 'ath_bcdiv'
            $num = $this->ath_bcdiv($num, (string)$base, 0);
        }

        return $result === '' ? '0' : $result;
    }

    /**
     * Checks if a matrix is an identity matrix.
     *
     * @param array $matrix The matrix to check (2D array of numbers in string format)
     * @return bool True if the matrix is an identity matrix, false otherwise
     */
    private function isIdentityMatrix(array $matrix): bool
    {
        $rows = count($matrix);
        $cols = count($matrix[0]);

        if ($rows !== $cols) {
            return false; // Must be square
        }

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($i === $j && $matrix[$i][$j] !== '1') {
                    return false; // Diagonal elements must be 1
                } elseif ($i !== $j && $matrix[$i][$j] !== '0') {
                    return false; // Non-diagonal elements must be 0
                }
            }
        }

        return true;
    }

    /**
     * Determines if a number is negative.
     *
     * @param string $num The number as a string
     * @return bool True if the number is negative, false otherwise
     */
    private function isNegative(string $num): bool
    {
        return $num[0] === '-';
    }

    /**
     * Checks if a matrix is orthogonal.
     *
     * @param array $matrix The matrix to check (2D array of numbers in string format)
     * @return bool True if the matrix is orthogonal, false otherwise
     */
    private function isOrthogonal(array $matrix): bool
    {
        // Get the transpose of the matrix
        $transpose = $this->transpose($matrix);

        // Multiply the matrix by its transpose
        $identity = $this->matrixMultiplication($matrix, $transpose);

        // Check if the result is the identity matrix
        return $this->isIdentityMatrix($identity);
    }

    /**
     * Helper function to check if a number is prime.
     *
     * @param string $num The number to check as a string
     * @return bool True if the number is prime, false otherwise
     */
    private function isPrime(string $num): bool
    {
        // Converti il numero in un intero per il controllo
        $n = (int)$num;

        // Numeri minori di 2 non sono primi
        if ($n < 2) {
            return false;
        }

        // Controlla se il numero è divisibile per qualsiasi numero da 2 a sqrt(n)
        for ($i = 2; $i * $i <= $n; $i++) {
            if ($n % $i === 0) {
                return false;
            }
        }

        // Se non è divisibile per nessun numero, allora è primo
        return true;
    }

    /**
     * Verifies if a vector is tangent to the manifold at a given point.
     * This is a placeholder for actual logic that would depend on the manifold's structure.
     *
     * @param array $vector The tangent vector to check
     * @param array $point The point on the manifold where the vector is tangent
     * @return bool True if the vector is tangent to the manifold at the given point
     */
    public function isTangentVector(array $vector, array $point): bool
    {
        // This is a placeholder function. In practice, this would involve checking the vector fields
        // and the manifold's local chart at the point to see if the vector is tangent.
        return true; // For now, assume the vector is tangent.
    }


    /**
     * Subtracts two numbers, determining the sign based on their magnitude.
     *
     * @param string $num1 The first number (assumed positive)
     * @param string $num2 The second number (assumed positive)
     * @return string The result of the subtraction with the correct sign
     */
    private function subtractWithSign(string $num1, string $num2): string
    {
        // Compare the absolute values
        $comparison = $this->ath_bccomp($num1, $num2);

        if ($comparison === 0) {
            return '0';  // They are equal, result is 0
        }

        // If num1 > num2, subtract normally
        if ($comparison > 0) {
            return $this->ath_bcsub($num1, $num2);
        }

        // If num1 < num2, subtract but result will be negative
        $difference = $this->ath_bcsub($num2, $num1);
        return '-' . $difference;
    }

    /**
     * Recursively adds two tensors element-wise.
     *
     * @param array|string $tensor1 The first tensor (or scalar value as string)
     * @param array|string $tensor2 The second tensor (or scalar value as string)
     * @return array|string The result of the addition
     */
    private function addTensorsRecursively(array|string $tensor1, array|string $tensor2): array|string
    {
        // If both elements are arrays, recurse deeper
        if (is_array($tensor1) && is_array($tensor2)) {
            $result = [];
            foreach ($tensor1 as $key => $value) {
                // Recursively add sub-elements
                $result[$key] = $this->addTensorsRecursively($tensor1[$key], $tensor2[$key]);
            }
            return $result;
        }

        // If they are not arrays, they must be scalar values, so add them
        return $this->ath_bcadd((string)$tensor1, (string)$tensor2);
    }


    /**
     * Helper function to check if two tensors have the same dimensions.
     *
     * @param array|string $tensor1 The first tensor
     * @param array|string $tensor2 The second tensor
     * @return bool True if they have the same dimensions, false otherwise
     */
    private function checkDimensions(array|string $tensor1, array|string $tensor2): bool
    {
        // If both are arrays, we recursively check dimensions of inner arrays
        if (is_array($tensor1) && is_array($tensor2)) {
            if (count($tensor1) !== count($tensor2)) {
                return false;
            }

            foreach ($tensor1 as $key => $value) {
                if (!$this->checkDimensions($tensor1[$key], $tensor2[$key])) {
                    return false;
                }
            }

            return true;
        }

        // If both are scalar values (not arrays), then dimensions match
        return !is_array($tensor1) && !is_array($tensor2);
    }

    /**
     * Transposes a matrix (switches rows and columns).
     *
     * @param array $matrix The matrix to transpose (2D array of numbers in string format)
     * @return array The transposed matrix
     */
    private function transpose(array $matrix): array
    {
        $transposed = [];

        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $value) {
                $transposed[$j][$i] = $value;
            }
        }

        return $transposed;
    }

    /**
     * Helper function to check if two matrices have the same dimensions.
     *
     * @param array $matrix1 The first matrix
     * @param array $matrix2 The second matrix
     * @return bool True if the matrices have the same dimensions, false otherwise
     */
    private function checkSameDimensions(array $matrix1, array $matrix2): bool
    {
        // Check if both matrices have the same number of rows
        if (count($matrix1) !== count($matrix2)) {
            return false;
        }

        // Check if each row has the same number of columns
        for ($i = 0; $i < count($matrix1); $i++) {
            if (count($matrix1[$i]) !== count($matrix2[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Performs the Kronecker product of two matrices.
     *
     * @param array $matrixA The first matrix
     * @param array $matrixB The second matrix
     * @return array The Kronecker product of the two matrices
     */
    private function kroneckerProduct(array $matrixA, array $matrixB): array
    {
        $result = [];
        $rowsA = count($matrixA);
        $colsA = count($matrixA[0]);
        $rowsB = count($matrixB);
        $colsB = count($matrixB[0]);

        // Loop through each element of matrixA
        for ($i = 0; $i < $rowsA; $i++) {
            for ($j = 0; $j < $colsA; $j++) {
                // Multiply each element of matrixA by the entire matrixB
                $elementA = $matrixA[$i][$j];
                for ($m = 0; $m < $rowsB; $m++) {
                    for ($n = 0; $n < $colsB; $n++) {
                        $result[$i * $rowsB + $m][$j * $colsB + $n] = $this->ath_bcmul($elementA, $matrixB[$m][$n]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Multiplies two matrices.
     *
     * @param array $matrix1 The first matrix
     * @param array $matrix2 The second matrix
     * @return array The result of the matrix multiplication
     */
    private function matrixMultiplication(array $matrix1, array $matrix2): array
    {
        $rows1 = count($matrix1);
        $cols1 = count($matrix1[0]);
        $rows2 = count($matrix2);
        $cols2 = count($matrix2[0]);

        if ($cols1 !== $rows2) {
            throw new \Exception('Matrix dimensions do not match for multiplication');
        }

        $result = array_fill(0, $rows1, array_fill(0, $cols2, '0'));

        // Multiply matrix1 by matrix2
        for ($i = 0; $i < $rows1; $i++) {
            for ($j = 0; $j < $cols2; $j++) {
                $sum = '0';
                for ($k = 0; $k < $cols1; $k++) {
                    $sum = $this->ath_bcadd($sum, $this->ath_bcmul($matrix1[$i][$k], $matrix2[$k][$j]));
                }
                $result[$i][$j] = $sum;
            }
        }

        return $result;
    }

    /**
     * Helper function to check if two tensors have the same shape.
     *
     * @param array $tensor1 The first tensor
     * @param array $tensor2 The second tensor
     * @return bool True if they have the same shape, false otherwise
     */
    private function checkSameShape(array $tensor1, array $tensor2): bool
    {
        // Check if both tensors are arrays
        if (is_array($tensor1) && is_array($tensor2)) {
            if (count($tensor1) !== count($tensor2)) {
                return false;
            }

            foreach ($tensor1 as $key => $value) {
                if (!$this->checkSameShape($tensor1[$key], $tensor2[$key])) {
                    return false;
                }
            }

            return true;
        }

        // If they are scalar values, shape is the same
        return !is_array($tensor1) && !is_array($tensor2);
    }

    /**
     * Parses an algebraic expression into individual terms.
     *
     * @param string $expression The algebraic expression as a string
     * @return array An associative array where the key is the variable term and the value is the coefficient
     */
    private function parseExpression(string $expression): array
    {
        $terms = [];
        $expression = str_replace(' ', '', $expression); // Remove spaces
        preg_match_all('/([+-]?\d*)([a-z]+)/', $expression, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $coefficient = $match[1] === '' || $match[1] === '+' ? '1' : ($match[1] === '-' ? '-1' : $match[1]);
            $variable = $match[2];
            $terms[$variable] = $this->ath_bcadd($terms[$variable] ?? '0', $coefficient);
        }

        return $terms;
    }

    /**
     * Combines like terms from two arrays of terms.
     *
     * @param array $terms1 The first array of terms
     * @param array $terms2 The second array of terms
     * @return array The combined array of terms
     */
    private function combineTerms(array $terms1, array $terms2): array
    {
        $combined = $terms1;

        foreach ($terms2 as $variable => $coefficient) {
            if (isset($combined[$variable])) {
                $combined[$variable] = $this->ath_bcadd($combined[$variable], $coefficient);
            } else {
                $combined[$variable] = $coefficient;
            }
        }

        return $combined;
    }

    /**
     * Reconstructs an algebraic expression from the combined terms.
     *
     * @param array $terms The array of terms
     * @return string The reconstructed algebraic expression
     */
    private function reconstructExpression(array $terms): string
    {
        $expression = '';
        foreach ($terms as $variable => $coefficient) {
            if ($coefficient === '1') {
                $expression .= "+$variable";
            } elseif ($coefficient === '-1') {
                $expression .= "-$variable";
            } elseif ($coefficient !== '0') {
                $expression .= ($coefficient[0] === '-' ? $coefficient : '+' . $coefficient) . $variable;
            }
        }

        // Remove leading "+" sign if present
        return ltrim($expression, '+');
    }

    /**
     * Parses a polynomial string into terms.
     *
     * @param string $polynomial The polynomial string
     * @return array An associative array where the key is the exponent and the value is the coefficient
     */
    private function parsePolynomial(string $polynomial): array
    {
        $terms = [];
        $polynomial = str_replace(' ', '', $polynomial); // Remove spaces

        // Regular expression to capture the coefficient, variable, and exponent
        preg_match_all('/([+-]?\d*)(x(\^(\d+))?)/', $polynomial, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $coefficient = $match[1] === '' || $match[1] === '+' ? '1' : ($match[1] === '-' ? '-1' : $match[1]);
            $exponent = isset($match[4]) ? (int)$match[4] : 1;  // Default exponent is 1 if not specified

            // Add the term to the terms array
            $terms[$exponent] = $this->ath_bcadd($terms[$exponent] ?? '0', $coefficient);
        }

        // Handle constant terms (no x)
        preg_match_all('/([+-]?\d+)(?!x)/', $polynomial, $constantMatches);
        if (!empty($constantMatches[1])) {
            foreach ($constantMatches[1] as $constant) {
                $terms[0] = $this->ath_bcadd($terms[0] ?? '0', $constant);
            }
        }

        return $terms;
    }

    /**
     * Combines the terms from two polynomials.
     *
     * @param array $terms1 The first polynomial's terms
     * @param array $terms2 The second polynomial's terms
     * @return array The combined polynomial terms
     */
    private function combinePolynomialTerms(array $terms1, array $terms2): array
    {
        $combined = $terms1;

        foreach ($terms2 as $exponent => $coefficient) {
            if (isset($combined[$exponent])) {
                $combined[$exponent] = $this->ath_bcadd($combined[$exponent], $coefficient);
            } else {
                $combined[$exponent] = $coefficient;
            }
        }

        return $combined;
    }

    /**
     * Reconstructs a polynomial string from the combined terms.
     *
     * @param array $terms The array of terms (exponent => coefficient)
     * @return string The reconstructed polynomial as a string
     */
    private function reconstructPolynomial(array $terms): string
    {
        // Sort terms by exponent in descending order
        krsort($terms);

        $polynomial = '';
        foreach ($terms as $exponent => $coefficient) {
            if ($coefficient !== '0') {
                if ($exponent == 0) {
                    $polynomial .= ($coefficient[0] === '-' ? $coefficient : '+' . $coefficient);
                } elseif ($exponent == 1) {
                    $polynomial .= ($coefficient == '1' ? '+x' : ($coefficient == '-1' ? '-x' : ($coefficient[0] === '-' ? $coefficient : '+' . $coefficient) . 'x'));
                } else {
                    $polynomial .= ($coefficient == '1' ? '+x^' . $exponent : ($coefficient == '-1' ? '-x^' . $exponent : ($coefficient[0] === '-' ? $coefficient : '+' . $coefficient) . 'x^' . $exponent));
                }
            }
        }

        // Remove the leading "+" sign if present
        return ltrim($polynomial, '+');
    }

    /**
     * Parses a symbolic expression into individual terms.
     *
     * @param string $expression The symbolic expression as a string
     * @return array An array where the key is the variable or function term and the value is the coefficient
     */
    private function parseSymbolicExpression(string $expression): array
    {
        $terms = [];
        $expression = str_replace(' ', '', $expression); // Remove spaces

        // Regular expression to match coefficients, variables, and functions
        preg_match_all('/([+-]?\d*)([a-zA-Z]+(?:\([^)]*\))?)/', $expression, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $coefficient = $match[1] === '' || $match[1] === '+' ? '1' : ($match[1] === '-' ? '-1' : $match[1]);
            $term = $match[2];
            $terms[$term] = $this->ath_bcadd($terms[$term] ?? '0', $coefficient);
        }

        return $terms;
    }

    /**
     * Combines like terms from two arrays of symbolic terms.
     *
     * @param array $terms1 The first symbolic expression's terms
     * @param array $terms2 The second symbolic expression's terms
     * @return array The combined array of symbolic terms
     */
    private function combineSymbolicTerms(array $terms1, array $terms2): array
    {
        $combined = $terms1;

        foreach ($terms2 as $term => $coefficient) {
            if (isset($combined[$term])) {
                $combined[$term] = $this->ath_bcadd($combined[$term], $coefficient);
            } else {
                $combined[$term] = $coefficient;
            }
        }

        return $combined;
    }

    /**
     * Reconstructs a symbolic expression from the combined terms.
     *
     * @param array $terms The array of terms (symbolic term => coefficient)
     * @return string The reconstructed symbolic expression
     */
    private function reconstructSymbolicExpression(array $terms): string
    {
        $expression = '';
        foreach ($terms as $term => $coefficient) {
            if ($coefficient !== '0') {
                if ($coefficient === '1') {
                    $expression .= "+$term";
                } elseif ($coefficient === '-1') {
                    $expression .= "-$term";
                } else {
                    $expression .= ($coefficient[0] === '-' ? $coefficient : '+' . $coefficient) . $term;
                }
            }
        }

        // Remove leading "+" sign if present
        return ltrim($expression, '+');
    }

    /**
     * Reconstructs the polynomial after combining like terms.
     *
     * @param array $terms The array of terms (degree => coefficient)
     * @return array The polynomial as a list of terms, sorted by degree
     */
    private function reconstructOrthogonalPolynomial(array $terms): array
    {
        // Sort terms by degree in descending order
        krsort($terms);

        // Remove zero coefficients
        $polynomial = [];
        foreach ($terms as $degree => $coefficient) {
            if ($coefficient !== '0') {
                $polynomial[$degree] = $coefficient;
            }
        }

        return $polynomial;
    }

    /**
     * Computes the factorial of a number using arbitrary precision arithmetic.
     *
     * @param string $num The number to compute the factorial of
     * @return string The factorial as a string
     */
    private function factorial(string $num): string
    {
        // Base case: 0! = 1
        if ($this->ath_bccomp($num, '0') === 0) {
            return '1';
        }

        // Iteratively compute factorial: num * (num-1) * ... * 1
        $result = '1';
        while ($this->ath_bccomp($num, '1') > 0) {
            $result = $this->ath_bcmul($result, $num);
            $num = $this->ath_bcsub($num, '1');
        }

        return $result;
    }

    /**
     * Helper function to add two arrays of coefficients term by term.
     *
     * @param array $coeff1 The first array of coefficients
     * @param array $coeff2 The second array of coefficients
     * @return array The result of the term-by-term addition
     */
    private function addCoefficientArrays(array $coeff1, array $coeff2): array
    {
        $maxLength = max(count($coeff1), count($coeff2));
        $result = [];

        for ($n = 0; $n < $maxLength; $n++) {
            $c1 = $n < count($coeff1) ? $coeff1[$n] : '0';
            $c2 = $n < count($coeff2) ? $coeff2[$n] : '0';

            $result[] = $this->ath_bcadd($c1, $c2);
        }

        return $result;
    }

    /**
     * Extended Euclidean algorithm to compute the greatest common divisor (gcd) of two numbers and find x and y
     * such that ax + by = gcd(a, b).
     *
     * @param string $a The first number
     * @param string $b The second number
     * @return array An array containing [gcd, x, y] such that ax + by = gcd(a, b)
     */
    private function extendedEuclidean(string $a, string $b): array
    {
        $x0 = '1';
        $x1 = '0';
        $y0 = '0';
        $y1 = '1';

        while ($this->ath_bccomp($b, '0') !== 0) {
            $q = $this->ath_bcdiv($a, $b);
            $r = $this->ath_bcmod($a, $b);
            $a = $b;
            $b = $r;

            $xTemp = $x0;
            $x0 = $x1;
            $x1 = $this->ath_bcsub($xTemp, $this->ath_bcmul($q, $x1));

            $yTemp = $y0;
            $y0 = $y1;
            $y1 = $this->ath_bcsub($yTemp, $this->ath_bcmul($q, $y1));
        }

        return [$a, $x0, $y0];
    }

    /**
     * Approximates the Gamma function using Stirling's approximation.
     *
     * Source: Approximation based on Stirling's formula for large values.
     *
     * @param float $alpha The input value for the Gamma function
     * @return float The approximation of Gamma(alpha)
     */
    private function gammaFunction(float $alpha): float
    {
        // Approssimazione di Stirling per grandi valori di alpha
        if ($alpha > 1) {
            return sqrt(2 * M_PI / $alpha) * pow($alpha / M_E, $alpha);
        } else {
            // Se alpha è piccolo, si può usare una forma numerica più precisa
            // Qui utilizziamo un'approssimazione semplice per alpha < 1
            return 1 / $alpha;
        }
    }

    /**
     * Recursively adds two brane surfaces component-wise.
     *
     * @param array $brane1 The first brane surface
     * @param array $brane2 The second brane surface
     * @return array The result of the addition
     */
    private function addBranesRecursively(array $brane1, array $brane2): array
    {
        $result = [];
        foreach ($brane1 as $key => $value) {
            if (is_array($value)) {
                // Se il valore è un array, continua la ricorsione
                $result[$key] = $this->addBranesRecursively($value, $brane2[$key]);
            } else {
                // Somma i valori scalari
                $result[$key] = $this->ath_bcadd($value, $brane2[$key]);
            }
        }
        return $result;
    }

    /**
     * Calculates the mean field generated by the spins.
     *
     * Source: Based on the standard mean field approximation.
     *
     * @param array $spins The array of spins (-1 or 1)
     * @return float The mean field value
     */
    private function calculateMeanField(array $spins): float
    {
        $totalSpin = array_sum($spins);
        $meanField = $totalSpin / count($spins); // Campo medio come media degli spin
        return floatval($meanField);
    }

    /**
     * Multiplies a matrix by a vector.
     *
     * Source: Standard matrix-vector multiplication for Markov chains.
     *
     * @param array $matrix The matrix to multiply
     * @param array $vector The vector to multiply by
     * @return array The resulting vector
     */
    private function multiplyMatrixVector(array $matrix, array $vector): array
    {
        $result = [];
        $numRows = count($matrix);

        for ($i = 0; $i < $numRows; $i++) {
            $sum = '0';
            foreach ($matrix[$i] as $j => $value) {
                $sum = $this->ath_bcadd($sum, $this->ath_bcmul($value, $vector[$j]));
            }
            $result[] = $sum;
        }

        return $result;
    }

    /**
     * Sigmoid activation function.
     *
     * @param float $x The input to the sigmoid function
     * @return float The sigmoid of the input
     */
    private function sigmoid(float $x): float
    {
        return 1 / (1 + exp(-$x));
    }

    /**
     * Updates the state of the Hopfield network based on the weighted sum of inputs.
     *
     * @param array $state The current state of the network
     * @param array $weights The weight matrix of the network
     * @return array The updated state vector
     */
    private function updateHopfieldState(array $state, array $weights): array
    {
        $newState = [];
        $numNeurons = count($state);

        for ($i = 0; $i < $numNeurons; $i++) {
            // Somma pesata degli input ricevuti dal neurone i
            $inputSum = '0';
            for ($j = 0; $j < $numNeurons; $j++) {
                if ($i !== $j) {
                    $inputSum = $this->ath_bcadd($inputSum, $this->ath_bcmul($weights[$i][$j], $state[$j]));
                }
            }

            // Funzione segno per aggiornare lo stato del neurone i
            $newState[$i] = $this->sign(floatval($inputSum));
        }

        return $newState;
    }

    /**
     * Sign function to determine the new state of a neuron.
     *
     * @param float $input The weighted sum input to the neuron
     * @return int Returns 1 if input > 0, -1 if input < 0, and 0 if input = 0
     */
    private function sign(float $input): int
    {
        if ($input > 0) {
            return 1;
        } elseif ($input < 0) {
            return -1;
        } else {
            return 0;
        }
    }

    /**
     * Calculates the probability of an observation for a single Gaussian component.
     *
     * Source: Standard Gaussian probability density function (PDF).
     *
     * @param array $x The observation (input vector)
     * @param array $mean The mean vector of the Gaussian component
     * @param array $covariance The covariance matrix of the Gaussian component
     * @return float The probability of the observation under this Gaussian component
     */
    private function gaussianProbability(array $x, array $mean, array $covariance): float
    {
        $dimension = count($x);

        // Calcolo della differenza (x - mean)
        $diff = [];
        for ($i = 0; $i < $dimension; $i++) {
            $diff[] = $this->ath_bcsub($x[$i], $mean[$i]);
        }

        // Determinante della matrice di covarianza (approssimazione)
        $determinant = $this->ath_bcmul($covariance[0][0], $covariance[1][1]);  // per semplicità, solo 2x2

        // Calcolo dell'esponente
        $exponent = '0';
        for ($i = 0; $i < $dimension; $i++) {
            $exponent = $this->ath_bcadd($exponent, $this->ath_bcmul($diff[$i], $diff[$i]));
        }
        $exponent = $this->ath_bcmul('-0.5', $exponent);

        // Calcola la probabilità usando la PDF gaussiana
        $normalizationFactor = $this->ath_bcmul('1', $this->ath_bcsqrt($determinant));
        $pdf = exp(floatval($exponent)) / $normalizationFactor;

        return $pdf;
    }

    /**
     * Helper function to approximate the inverse of a Laplace term.
     *
     * @param string $laplaceTerm The Laplace term to invert
     * @return string The approximated time-domain expression
     */
    private function approximateInverse(string $laplaceTerm): string
    {
        // Approssimazione per funzioni esponenziali e polinomiali di base
        return "e^(-t) * ($laplaceTerm)";  // Esempio semplificato per illustrarne l'uso
    }
}

class PetriNet
{
    public $places;       // Array of places (each place has a number of tokens)
    public $transitions;  // Array of transitions (each transition connects places)

    public function __construct(array $places, array $transitions)
    {
        $this->places = $places;
        $this->transitions = $transitions;
    }
}
