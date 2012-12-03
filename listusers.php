<?php
	header('Content-type: text/html;');
	
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	

	$users = Monitoring::getInvolvedUsers();
	$count = count($users);
	if($count == 0) {
?>
		<p>Nessun partecipante ad attivit&agrave; formativa registrato.</p>
<?php
	} else {
?>
		<p><?php echo TextUtils::escape($count . ' partecipanti all\'attività formativa registrati.'); ?></p>
		<table class="courses" cellspacing="0" align="center">
			<tr><th>Cognome</th><th>Nome</th><th>Sesso</th><th>Grado</th><th>Codice SIP</th><th></th></tr>
<?php
		$i = 0;
		foreach ($users as $user) {
?>
			<tr class="<?php echo ((($i++ % 2 ) == 0) ? 'even' : 'odd');?>">
				<td align="center"><?php echo $user->getSurname();?></td>
				<td align="center"><?php echo $user->getName();?></td>
				<td align="center"><?php echo ($user->getSex() == 1 ? 'M' : 'F');?></td>
				<td align="center"><?php echo $user->getLongRankDescription();?></td>
				<td align="center"><?php echo $user->getId();?></td>
			</tr>
<?php
		}
	}
?>
		</table>