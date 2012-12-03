<?php

	class Factory {
		
		public static function getCoursesForUser($user, $subject) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
		
			$courses = array();
		
			// create statement object
			$stmt = $mysql->stmt_init();
		
			if($stmt->prepare(
				"select 
					c.id, c.title, c.description, c.url, c.role_id, r.description
				from 
					course as c 
				inner join 
						authorisations as a 
					on 
						c.role_id = a.role_id 
				inner join 
						role as r 
					on 
						c.role_id = r.id 
				where 
					a.user_id = ?
				and
					c.published = 1
				and
					subject_id = ?
				order by 
					c.id asc")) {

				
				$user_id = $user->getId();
				$stmt->bind_param('ii', $user_id, $subject);
				$stmt->execute();			
				$course_id = 0;
				$course_title = "";
				$course_description = "";
				$course_url = "";
				$course_role_id = 0;
				$course_role_description = "";
				$stmt->bind_result($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
				while($stmt->fetch()) {
					$course = new Course($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
					array_push($courses, $course);
				}
				$stmt->close();
			}				
			return $courses;
		}
		
		public static function getCourseForId($id) {

			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
		
			// create statement object
			$stmt = $mysql->stmt_init();
			if($stmt->prepare(
				"select 
					c.id, c.title, c.description, c.url, c.role_id, r.description 
				from 
					course as c 
				inner join
					role as r
				on 
					c.role_id = r.id 
				where 
					c.id = ?
				and
					c.published = 1")) {
				
				// bind the variables to replace the ?s
				$stmt->bind_param('i', $id);
				
				// execute query
				$stmt->execute();
							
				$course_id = $id;
				$course_title = "";
				$course_description = "";
				$course_url = "";
				$course_role_id = 0;
				$course_role_description = "";
				
				// bind your result columns to variables
				$stmt->bind_result($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
			
				// fetch the result of the query
				$stmt->fetch();
				
				// close statement object
				$stmt->close();
				
				return new Course($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
			}						
		}
		
		public static function getElementForId($id) {

			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
		
			// create statement object
			$stmt = $mysql->stmt_init();
			if($stmt->prepare(
				"select 
					id, ordinal, title, duration, url, course_id
				from 
					element 
				where 
					id = ?")) {
				
				// bind the variables to replace the ?s
				$stmt->bind_param('i', $id);
				
				// execute query
				$stmt->execute();

				$element_id = $id;
				$element_ordinal = 0;
				$element_title = "";
				$element_duration = 0;
				$element_url = "";				
				$course_id = 0;
				
				// bind your result columns to variables
				$stmt->bind_result($element_id, $element_ordinal, $element_title, $element_duration, $element_url, $course_id);
			
				// fetch the result of the query
				$stmt->fetch();
				
				// close statement object
				$stmt->close();
				
				return new Element($element_id, $element_ordinal, $element_title, $element_duration, $element_url, $course_id);
			}						
		}
	}
?>
