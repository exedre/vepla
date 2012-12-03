<?php
	header('Content-type: text/html;');
	
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
		
	$course = Course::getCourseForId($_REQUEST['courseid']);
	$elements = $course->getElements();	
	

	echo '<p><b>' . TextUtils::escape($course->getTitle()) . '</b></p>';
	echo '<p><i>' . TextUtils::escape($course->getDescription()) . '</i></p>';
	
	$count = count($elements);
	if($count == 0) {
		echo '<p>Il corso è privo di elementi formativi.</p>';
	} else {
		echo '<p>Il corso è composto da ' . $count . ($count == 1 ? ' elemento' : ' elementi') . ':</p>';
		echo '<table class="courses" cellspacing="0" align="center">';
		echo '<tr><th>&nbsp;</th><th>Titolo</th><th>Tempo di Fruizione</th></tr>';
		$i = 0;
		foreach ($elements as $element) {
			echo '<tr class="' . ((($i++ % 2 ) == 0) ? 'even' : 'odd') . '">';
			echo '<td align="center"><img class="active" src="images/' . ($element->isViewed() ? 'ok' : 'lens') . '_small.png" height="24px" width="24px" onclick="goToFirstElement(' . $element->getId() . ')" alt="' . ($element->isViewed() ? 'già fruito' : 'da fruire') . '" title="' . ($element->isViewed() ? 'già fruito' : 'da fruire') . '"></img></td>';
			echo '<td><span class="active" onmouseover="this.style.color=\'#9933cc\';" onmouseout="this.style.color=\'\';" onclick="goToFirstElement(' . $element->getId() . ')">' . TextUtils::escape($element->getTitle()) . '</span></td>';			
			echo '<td align="center" width="20%">' . $element->getFormattedViewDuration() . /*' (' . $element->getViewDuration() . ')' . */'</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
?>
	<!--<p align="center"><a href="index.php"><img src="images/arrow_up.png">UP</img></a><a href="index.php">Torna all'elenco dei corsi</a></p>-->
	<table align="center">
		<tr>
			<td><a class="uplink" href="index.php"><img class="active" border="block" src="images/arrow_up.png" height="16px" width="16px"></img></a></td>
			<td><a class="uplink" href="index.php" style="font-size: 12px;">Torna all'elenco dei corsi</a></td>
		</tr>
	</table>
