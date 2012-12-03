<?php

	class MathUtils {
	
		public static function divide ( $numerator, $denominator ) {
			if($numerator == $denominator) {
				// denominator fits exactly once into numerator, thus result is 1, remainder is 0
				//echo 'dividing ' . $numerator . ' : ' . $denominator . ' = 1 R 0<br>';
				return array(1, 0);		
			} else if($numerator < $denominator) {
				// denominator fits 0 times into the numerator, and it's all remainder
				//echo 'dividing ' . $numerator . ' : ' . $denominator . ' = 0 R ' . $numerator . '<br>';
				return array(0, $numerator);	
			}
			$remainder = $numerator % $denominator;
			$result =  ( $numerator - $remainder ) / $denominator;
			//echo 'dividing ' . $numerator . ' : ' . $denominator . ' = ' . $result . ' R ' . $remainder . '<br>';
			return array ($result, $remainder);
		}
	}
?>
