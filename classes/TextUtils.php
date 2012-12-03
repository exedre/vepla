<?php

	class TextUtils {
		
		public static function escape($string) {
			$escaped = htmlentities($string);
			$escaped = str_replace ( '&lt;b&gt;', '<b>' , $escaped );
			$escaped = str_replace ( '&lt;B&gt;', '<b>' , $escaped );
			$escaped = str_replace ( '&lt;/b&gt;', '</b>' , $escaped );
			$escaped = str_replace ( '&lt;/B&gt;', '</b>' , $escaped );
			$escaped = str_replace ( '&lt;i&gt;', '<i>' , $escaped );
			$escaped = str_replace ( '&lt;I&gt;', '<i>' , $escaped );
			$escaped = str_replace ( '&lt;/i&gt;', '</i>' , $escaped );
			$escaped = str_replace ( '&lt;/I&gt;', '</i>' , $escaped );
			return $escaped;
		}
	}
?>
