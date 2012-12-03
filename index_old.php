<?php 
	header('Content-type: text/html;');
	// business logic
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
	$user = User::getCurrentUser();
?>

<html>
	<head>
		<title>Ambiente formativo online dell'Area R.E.R.I.</title>
		<link rel="icon" type="image/png" href="images/favicon.png" />		
		<link rel="stylesheet" type="text/css" href="css/patstat.css">
		<link rel="stylesheet" type="text/css" href="css/submodal.css" />
		<script type="text/javascript" src="js/siparium.js"></script>
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
			<!--<div id="logo"><p><img src="http://www.repubblica.it/static/images/homepage/2010/la-repubblica-logo-home-payoff.png?new" alt="Logo de la Repubblica" width="432" height="90"></p></div>-->
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
				<td align="center" width="5%" class="current">HOME</td>
				<td width="*">&nbsp;</td>
				<td align="center" width="5%"><?php if($user->hasRole(1)) echo '<a href="monitor.php">Monitoraggio</a>'; else echo '&nbsp;';?></td>
				<td align="center" width="5%"><?php if($user->hasRole(2)) echo '<a href="admin.php">Sistema</a>'; else echo '&nbsp;';?></td>
			</tr>
		</table>

		<!-- contents -->
		<div id="contents">
		
			<p>Sono disponibili i seguenti supporti:</p>
			
			<table class="manuals" cols="2" align="center" width="40%">
				<tr>
					<td width="5%"><a border="0px" href="courses.php"><img border="0px" src="images/goto_soff.png" alt="Corsi autodidattici" text="Corsi autodidattici" onmouseover="this.src='images/goto.png'" onmouseout="this.src='images/goto_soff.png'"/></a></td>
					<td align="left"><a href="courses.php">Corsi Autodidattici sui Brevetti</a></td>					
				<tr>
				<!--
				<tr>
					<td width="5%"><a border="0px" href="tutorials.php"><img border="0px" src="images/goto_soff.png" alt="Tutorial e Manuali per l'Addetto di Segreteria" text="Tutorial e Manuali per l'Addetto di Segreteria" onmouseover="this.src='images/goto.png'" onmouseout="this.src='images/goto_soff.png'"/></a></td>
					<td align="left"><a href="tutorials.php">Tutorial e Manuali per l'Addetto di Segreteria</a></td>					
				<tr>
				<tr>
					<td width="5%"><a border="0px" href="guru.php"><img border="0px" src="images/goto_soff.png" alt="Documentazione sui Profili GURU" text="Documentazione sui Profili GURU" onmouseover="this.src='images/goto.png'" onmouseout="this.src='images/goto_soff.png'"/></a></td>
					<td align="left"><a href="guru.php">Documentazione relativa ai profili GURU</a></td>					
				<tr>
				<tr>
					<td width="5%"><a border="0px" href="pdf/deleghe.pdf"><img border="0px" src="images/pdf_soff.png" alt="Guida alle Deleghe" text="Guida alle Deleghe"  onmouseover="this.src='images/pdf_large.png'" onmouseout="this.src='images/pdf_soff.png'"/></a></td>
					<td align="left"><a href="pdf/deleghe.pdf">Guida all'utilizzo della funzione di &quot;delega&quot;</a></td>					
				<tr>
				<tr>
					<td width="5%"><a border="0px" href="pdf/competenze.pdf" target="_blank"><img border="0px" src="images/pdf_soff.png" alt="Competenze sui processi SIPARIUM" text="Competenze sui processi SIPARIUM" onmouseover="this.src='images/pdf_large.png'" onmouseout="this.src='images/pdf_soff.png'"/></a></td>
					<td align="left"><a href="pdf/competenze.pdf" target="_blank">SIPARIUM - Competenze sui processi</a></td>					
				<tr>
				<tr>
					<td width="5%"><a border="0px" href="jaws/index.html"><img border="0px" src="images/goto_soff.png" alt="Guide JAWS per SIPARIUM" text="Guide JAWS per SIPARIUM" onmouseover="this.src='images/goto.png'" onmouseout="this.src='images/goto_soff.png'"/></a></td>
					<td align="left"><a href="jaws/index.html">Guide JAWS per SIPARIUM</a></td>					
				<tr>
				-->
			</table>
			
			<p>&nbsp;</p>
		</div>
	
		<!-- footer -->
		<div id="footer" class="center">Copyright 2011-2012 (C) Banca d'Italia</div>
	</body>
</html>
