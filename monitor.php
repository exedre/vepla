<?php 
	header('Content-type: text/html;');
	// business logic
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
	$user = User::getCurrentUser();
	$subject = 0; // patstat
?>

<html>
	<head>
		<title>PatStat - Funzionalit&agrave; di Monitoraggio</title>
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
				<td align="center" width="5%"><a href="index.php">Corsi</a></td>
				<!--
				<td align="center" width="5%"><a href="courses.php">CORSI</a></td>
				<td align="center" width="5%"><a href="tutorials.php">TUTORIAL</a></td>
				<td align="center" width="5%"><a href="manuals.php">MANUALI</a></td>
				<td align="center" width="5%"><a href="guru.php">GURU</a></td>
				-->
				<td width="*">&nbsp;</td>
				<!--
				<td align="center" class ="current" width="5%">Monitoraggio</td>				
				<td align="center" width="5%"><?php if($user->hasRole(2)) echo '<a href="admin.php">Sistema</a>'; else echo '&nbsp;';?></td>
				-->
			</tr>
		</table>

		<!-- contents -->
		<div id="contents">
		
			<p>Funzionalit&agrave; di Monitoraggio della Formazione</p>
	
			<table class="courses" style="width: 60%" align="center">
				<?php
					if(isset($_REQUEST['showusers'])) 
					$i = 0; 
					$courses = Course::getAllPublishedCourses($subject);
				?>
				<tr><th>Indicatore</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>
				
				<tr class="<?php echo ((($i++ % 2 ) == 0) ? 'even' : 'odd')?>">
					<td align="left">Numero totale di corsi pubblicati</td>
					<td align="center"><?php echo count($courses);?></td>					
					<td align="center">&nbsp;</td>
					<td align="center">&nbsp;</td>
				</tr>
				<tr class="<?php echo ((($i++ % 2 ) == 0) ? 'even' : 'odd')?>">
					<td align="left">Totale su tutti i corsi</td>
					<td align="center">coinvolte <b><?php echo Monitoring::getInvolvedUsersCount($subject);?></b> persone</td>
					<td align="center">per un tempo totale di <b><?php echo TimeUtils::format(Monitoring::getTotalLearningTime($subject));?></b></td>
					<td align="center"><span class="active" onclick="goToChart('coursepie.php')"><img src="images/chart.png" height="16px" width="16px"></img></span></td>
				</tr>
				
<?php			foreach($courses as $course) { ?>
				<tr class="<?php echo ((($i++ % 2 ) == 0) ? 'even' : 'odd')?>">
					<td align="left">Corso <b><?php echo TextUtils::escape($course->getTitle());?></b></td>
					<td align="center">coinvolte <b><?php echo Monitoring::getInvolvedUsersCountOnCourse($course->getId());?></b> persone</td>
					<td align="center">per un tempo totale di <b><?php echo TimeUtils::format(Monitoring::getTotalLearningTimeOnCourse($course->getId()));?></b></td>
					<td align="center">&nbsp;</td>
				</tr>				
<?php			}	?>				
			</table>
			<p></p>
			<table align="center">
				<tr>
					<td><a class="uplink" href="monitor.php"><img class="active" border="block" src="images/reload.png" height="16px" width="16px"></img></a></td>
					<td><a class="uplink" href="monitor.php" style="font-size: 12px;">Aggiorna</a></td>
				</tr>
			</table>
			
			<p align="center">
				<input type="checkbox" name="showusers" id="showusers" value="showusers" onclick="showUserParticipations();"/>Mostra l'elenco di utenti coinvolti nella formazione
			</p>
			<div id="showusers_area">				
			</div>			
		</div>
	
		<!-- footer -->
		<div id="footer" class="center">Copyright 2011-2012 (C) Banca d'Italia</div>
	</body>
</html>
