<?php 
	header('Content-type: text/html;');
	// business logic
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
	$user = User::getCurrentUser();
	$subject = 0;	// patstat
?>

<html>
	<head>
		<title>PatStat</title>
		<link rel="icon" type="image/png" href="images/favicon.png" />		
		<link rel="stylesheet" type="text/css" href="css/patstat.css">
		<link rel="stylesheet" type="text/css" href="css/submodal.css" />
		<script type="text/javascript" src="js/patstat.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
		<script type="text/javascript" src="js/submodal.js"></script>
		<script type="text/javascript">
			// window.history.forward();
			function noBack() { 
				// window.history.forward(); 
			}
		</script>
	</head>
	<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
		<!-- topmost stripe -->
		<table class="info">
			<tr>
				<td>&nbsp;</td>
				<td align="right"><?php echo "Benvenut" . ($user->isMale() ? "o" : "a") . ", " . $user->getName() . "!"; ?></td>
			</tr>
		</table>

		<!-- top section -->
		<div id="top">
			<div id="logo"><p><img src="images/patpending_large.png" alt="Logo di PatPending" width="615" height="90"></p></div>
			<div id="user">
				<table class="userinfo">
					<tr><td colspan="2" align="center"><img src="images/<?php echo (($user->isMale()) ? 'boy' : 'girl');?>.png" height="32px" width="32px"></img></td></tr>
					<tr><td align="right">utente:&nbsp;</td><td><?php echo $user->getName() . ' ' . $user->getSurname();?></td></tr>
					<tr><td align="right">login:&nbsp;</td><td><?php echo $user->getLogin();?></td></tr>
					<tr><td align="right">CID:&nbsp;</td><td><?php echo $user->getId();?></td></tr>
					<tr><td align="right">grado:&nbsp;</td><td><?php echo $user->getShortRankDescription();?></td></tr>
				</table>
			</div>
		</div>
		
		<!-- breadcrumble stripe with menus -->
		<table class="info">
			<tr>
				<td align="center" width="5%"><?php if($user->hasRole(1)) echo '<a href="monitor.php">Monitoraggio</a>'; else echo '&nbsp;';?></td>			
				<!--<td align="center" width="5%"><a href="index.php">HOME</a></td>-->
				<!--<td align="center" width="5%" class="current" class="active" onmouseover="this.style.color='#FF0000'; this.style.cursor='pointer';" onmouseout="this.style.color='#FF7F50'; this.style.cursor='default'" onclick="window.location='courses.php'">CORSI</td>-->
				<td width="*">&nbsp;</td>
				<!--<td align="center" width="5%"><?php if($user->hasRole(2)) echo '<a href="admin.php">Sistema</a>'; else echo '&nbsp;';?></td>-->
			</tr>
		</table>

		<!-- contents -->
		<div id="contents">
		
		
<?php
			if(!isset($_REQUEST['courseid'])) {
				// should never get here
				$courses = Factory::getCoursesForUser($user, $subject);
				if(count($courses)) {
					echo '<p>Nell\'ambiente formativo online sono disponibili i seguenti corsi:</p>';
					echo '<table class="courses" cellspacing="0" align="center">';
					echo '<tr><th>&nbsp;</th><th>&nbsp;</th><th>Titolo</th><th>Descrizione</th><th>Destinato a</th></tr>';
					$i = 0;
					foreach ($courses as $course) {
						echo '<tr class="' . ((($i++ % 2 ) == 0) ? 'even' : 'odd') . '">';					
						echo '<td><img class="active" src="images/video.png" height="24px" width="24px" onclick="goToCourse(' . $course->getId() . ')"></img></td>';
						echo '<td>' . $course->getId() . '.&nbsp;</td>';
						
						echo '<td><span class="active" onmouseover="this.style.color=\'#9933cc\';" onmouseout="this.style.color=\'\';" onclick="goToCourse(' . $course->getId() . ')">' . TextUtils::escape($course->getTitle()) .  '</span></td>';
						echo '<td>' . TextUtils::escape($course->getDescription()) . '</td>';
						echo '<td>' . TextUtils::escape($course->getRoleDescription()) . '</td>';
						echo '</tr>';
					}
					echo '</table>';
				} else {
					echo 'Non è disponibile alcun corso nell\'ambiente formativo online.';
				}
			} else {
				$courses = Factory::getCoursesForUser($user, $subject);
				$found = false;
				foreach($courses as $course) {
					if($course->getId() == $_REQUEST['courseid']) {
						//$course = Factory::getCourseForId($_REQUEST['courseid']);
						echo '<script type="text/javascript">goToCourse(' . $course->getId() . ')</script>'; 
						$found = true;
					}
				}
				if(!$found) {
					echo '<p>' . TextUtils::escape('L\'utente non è autorizzato ad accedere al corso.') . '</p>';
				}
			}			
?>
		</div>
	
		<!-- footer -->
		<div id="footer" class="center">Copyright 2011-2012 (C) Banca d'Italia</div>
	</body>
</html>
