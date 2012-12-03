<?php
	header('Content-type: text/html;');
	
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
	
	$course = Course::getCurrentCourse();
	$element = $course->getElement($_REQUEST['elementid']);

	$movie = "";
	if($element->getContentType() == 0) {
		$movie = '/biblio/content/patstat/' . $course->getId() . '/' . $element->getOrdinal() . '/element.swf';
	} else if($element->getContentType() == 1) {
		$movie = '/biblio/content/patstat/' . $course->getId() . '/' . $element->getOrdinal() . '/element.mov';
	}
	$name = 'part_' . $course->getId() . "_" . $element->getOrdinal();

?>
<html>
	<head>
		<title>Elemento didattico</title>
		<link rel="icon" type="image/png" href="images/favicon.png" />		
		<link rel="stylesheet" type="text/css" href="css/patstat.css" />
		<script type="text/javascript" src="js/patstat.js"></script>
	</head>
	<body onload="timer.start();" onunload='registerProgress(<?php echo $element->getId();?>, <?php echo $element->getCourseId();?>)'>
		<h3><?php echo TextUtils::escape($element->getTitle()); ?></h3>
		<p>tempo di fruizione fino ad ora: <?php echo $element->getFormattedViewDuration(); ?></p>
		<div align="center">		
		<?php if ($element->getContentType() == 0) { ?>
			<object 
				classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" 
				codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" 
				width="770" height="560" id="part" align="center">
				<param name="movie" value="<?php echo $movie;?>">
				<param name="quality" value="high"> 
				<param name="bgcolor" value="#ffffff"> 
				<param name="menu" value="false">			
				<embed src="<?php echo $movie; ?>" quality="high" bgcolor="#ffffff" width="770" height="560" 
					name="<?php echo $name;?>" align="center" type="application/x-shockwave-flash" 
					pluginspage="http://www.macromedia.com/go/getflashplayer"></embed> 
			</object> 		
		<? } else if ($element->getContentType() == 1) { ?>
			<embed 
				src="<?php echo $movie; ?>" 
				width=770 
				height=560
				autoplay=true
				controller=true
				loop=false
				pluginspage="http://www.apple.com/quicktime"/>
		
		<?php } ?>
		</div>
		<!-- bottom navigator -->
		<table class="navtable">
			<tr>
				<td class="navbutton" align="left">
<?php 
		$prev = $course->getPreviousElementId($element);
		if(isset($prev)) {
			echo '<img src="images/prev_soff.png" width="32" height="32" title="precedente" alt="precedente"'; 
			echo '	onmouseover="grow(this, \'images/prev_lon.png\');"';
			echo '	onmouseout="shrink(this, \'images/prev_soff.png\');"';			
			echo '	onclick="goToElement(' . $element->getId() . ', ' . $prev . ');"';
			echo '	/>';			
		} else {
			echo '&nbsp;';
		}
?>
				</td>
<?php
		if(isset($prev)) {
			echo '	<td class="navtext">Precedente</td>';
		} else {
			echo '	<td class="navtext">&nbsp;</td>';
		}
?>				
				<td><p id="timer" style="font-size: 10px" align="center">avanzamento: 0 sec</p></td>
<?php 
		$next = $course->getNextElementId($element);
		if(isset($next)) {
			echo '	<td class="navtext">Successivo</td>';
		} else {
			echo '	<td class="navtext">Chiudi</td>';
		}		
		if(isset($next)) {
			echo '<td class="navbutton" align="right">';
			echo '<img src="images/next_soff.png" width="32" height="32" title="successivo" alt="successivo"'; 
			echo '	onmouseover="grow(this, \'images/next_lon.png\');"';
			echo '	onmouseout="shrink(this, \'images/next_soff.png\');"';			
			echo '	onclick="goToElement(' . $element->getId() . ', ' . $next . ');"';
			echo '	/>';			
		} else {
			echo '<td class="navbutton" align="right">';
			echo '<img src="images/close_soff.png" width="32" height="32" title="chiudi" alt="chiudi"'; 
			echo '	onmouseover="grow(this, \'images/close_lon.png\');"';
			echo '	onmouseout="shrink(this, \'images/close_soff.png\');"';			
			echo '	onclick="closePopup();"';
			echo '	/>';						
		}			
?>		
				</td>
			</tr>
		</table>
		
	</body>
</html>
