<?php

namespace FourthPlanetDev\MathUtils;

	/**
	 * Returns true if $numerator/$denominator results in a repeating decimal
	 * @param int $numerator - The numerator
	 * @param int $denominator - The denominator
	 * @param number $base - The base we are using.  1/3 in base 10 may be repeating, but it isn't in base 6
	 * @return boolean
	 */
	function fractionRepeats($numerator,$denominator,$base=10) {
		list($numerator,$denominator) = reduceFraction($numerator,$denominator);
		// factor $denominator
		return !empty(array_diff(findPrimeFactors($denominator, true),findPrimeFactors($base,true)));
	}

	/* Shamelessly stolen from https://stackoverflow.com/questions/12412782/simplify-a-fraction*/
	/**
	 * Reduces a fraction.
	 * @param int $numerator
	 * @param int $denominator
	 * @return array of the reduced numerator and denominator
	 */
	function reduceFraction($numerator,$denominator) {
		$gcd = gcd($numerator,$denominator);
		return array($numerator/$gcd,$denominator/$gcd);
	}

	/**
	 * Returns the greatest common denominator between 2 numbers
	 * @param int $a
	 * @param int $b
	 * @return int
	 */
	function gcd($a,$b) {
		$a = abs($a); $b = abs($b);
		if( $a < $b) list($b,$a) = Array($a,$b);
		if( $b == 0) return $a;
		$r = $a % $b;
		while($r > 0) {
			$a = $b;
			$b = $r;
			$r = $a % $b;
		}
		return $b;
	}

	/**
	 * Finds the prime factors
	 * @param int $number - The number to factor
	 * @param boolean $unique=false.  When true, returns unique factors only
	 * @return array
	 * @example findPrimeFactors(8) = array(2,2,2)
	 * @example findPrimeFactors(8,true) = array(2)
	 */
	function findPrimeFactors($number,$unique=false) {
		if ($number < 2) return false;
		elseif (static::_isPrime($number)) return array($number);

		$primes = static::_getPrimesUpTo($number/2);

		$factors = array();
		foreach($primes as $prime) {
			while ($number % $prime == 0 ) {
				$factors[] = $prime;
				$number /= $prime;
			}
			if ($number < $prime) break;
		}
		return $unique ? array_values(array_unique($factors)) : $factors;
	}

	/**
	 * Returns true if number is prime
	 * @param int $number
	 * @return boolean
	 * */
	function isPrime($number) {
		$primes = static::_getPrimesUpTo($number);
		return array_pop($primes) == $number;
	}

	/**
	 * Implements Sieve of Eratosthenes
	 * @param int $number
	 * @return array
	 * */
	function getPrimesUpTo($number) {
		$primes = array();
		$check = 2;
		while ($check <= $number) {
			if (empty($primes)) $primes[] = $check;
			else {
				$is_prime = true;
				foreach($primes as $prime)
					if ($check % $prime == 0) {
						$is_prime = false;
						break;
					}
				if ($is_prime) $primes[] = $check;
			}
			$check++;
		}
		return $primes;
	}
?>