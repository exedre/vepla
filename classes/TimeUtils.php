<?php

	class TimeUtils {
		public static function format($time) {
			if($time == 0) {
				return '0 sec';
			}
			list($hours, $remainder) = MathUtils::divide($time, 3600);
			list($mins, $secs) = MathUtils::divide($remainder, 60);
			$result = '';
			if($hours > 0) {
				if($hours == 1) $result = $result . '1 ora ';
				else $result = $result . $hours . ' ore ';
			}
			if($mins > 0) $result = $result . $mins . ' min ';
			$result = $result . $secs . ' sec';
			return $result;			
		}
	}
?>
