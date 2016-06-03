<?php
namespace Math\Statistics;

/**
 * Statistical averages
 * Mean, median, and mode
 */
class Average
{
    /**
     * Calculate the mean average of a list of numbers
     *
     *     ∑⟮xᵢ⟯
     * x̄ = -----
     *       n
     *
     * @param array $numbers
     *
     * @return number
     */
    public static function mean(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }
        return array_sum($numbers) / count($numbers);
    }

    /**
     * Calculate the median average of a list of numbers
     *
     * @param array $numbers
     *
     * @return number
     */
    public static function median(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        // Reset the array key indexes because we don't know what might be passed in
        $numbers = array_values($numbers);

        // For odd number of numbers, take the middle indexed number
        if (count($numbers) % 2 == 1) {
            $middle_index = intdiv(count($numbers), 2);
            sort($numbers);
            return $numbers[$middle_index];
        }

        // For even number of items, take the mean of the middle two indexed numbers
        $left_middle_index  = intdiv(count($numbers), 2) - 1;
        $right_middle_index = $left_middle_index + 1;
        sort($numbers);
        return self::mean([ $numbers[$left_middle_index], $numbers[$right_middle_index] ]);
    }

    /**
     * Calculate the mode average of a list of numbers
     * If multiple modes (bimodal, trimodal, etc.), all modes will be returned.
     * Always returns an array, even if only one mode.
     *
     * @param array $numbers
     *
     * @return array of mode(s)
     */
    public static function mode(array $numbers): array
    {
        if (empty($numbers)) {
            return [];
        }

        // Count how many times each number occurs
        // Determine the max any number occurs
        // Find all numbers that occur max times
        $number_counts = array_count_values($numbers);
        $max           = max($number_counts);
        $modes         = array();
        foreach ($number_counts as $number => $count) {
            if ($count === $max) {
                $modes[] = $number;
            }
        }
        return $modes;
    }

    /**
     * Geometric mean
     * A type of mean which indicates the central tendency or typical value of a set of numbers
     * by using the product of their values (as opposed to the arithmetic mean which uses their sum).
     * https://en.wikipedia.org/wiki/Geometric_mean
     *                    __________
     * Geometric mean = ⁿ√a₀a₁a₂ ⋯
     *
     * @param  array  $numbers
     * @return number
     */
    public static function geometricMean(array $numbers)
    {
        if (empty($numbers)) {
            return null;
        }

        $n = count($numbers);
        return pow(array_reduce($numbers, function ($carry, $a) { return !empty($carry) ? $carry * $a : $a; }), 1/$n);
    }

    /**
     * Arithmetic-Geometric mean
     *
     * First, compute the arithmetic and geometric means of x and y, calling them a₁ and g₁ respectively.
     * Then, use iteration, with a₁ taking the place of x and g₁ taking the place of y.
     * Both a and g will converge to the same mean.
     * https://en.wikipedia.org/wiki/Arithmetic%E2%80%93geometric_mean
     *
     * x and y ≥ 0
     * If x or y = 0, then agm = 0
     * If x or y < 0, then NaN
     *
     * @param  number $x
     * @param  number $y
     * @return float
     */
    public static function arithmeticGeometricMean($x, $y): float
    {
        // x or y < 0 = NaN
        if ($x < 0 || $y < 0) {
            return \NAN;
        }

        // x or y zero = 0
        if ($x == 0 || $y == 0) {
            return 0;
        }

        // Standard case x and y > 0
        list($a, $g) = [$x, $y];
        foreach (range(1, 10) as $_) {
            list($a, $g) = [self::mean([$a, $g]), self::geometricMean([$a, $g])];
        }
        return $a;
    }

    /**
     * Convenience method for arithmeticGeometricMean
     *
     * @param  number $x
     * @param  number $y
     * @return float
     */
    public static function agm($x, $y): float
    {
        return self::arithmeticGeometricMean($x, $y);
    }

    /**
     * Get a report of all the averages over a list of numbers
     * Includes mean, median and mode
     *
     * @param array $numbers
     *
     * @return array [ mean, median, mode ]
     */
    public static function getAverages(array $numbers): array
    {
        return [
            'mean'           => self::mean($numbers),
            'median'         => self::median($numbers),
            'mode'           => self::mode($numbers),
            'geometric_mean' => self::geometricMean($numbers),
        ];
    }
}
