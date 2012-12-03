<?php
	header('Content-type: text/html;');
	
	session_start();
	function __autoload($class_name) {
		require_once "classes/" . $class_name . '.php';
	}	
	
	$user = User::getCurrentUser();
	$course = Course::getCurrentCourse();
	$element = $course->getElement($_REQUEST['elemid']);
	$element->addToViewDuration($_REQUEST['duration'], $user);
?>
