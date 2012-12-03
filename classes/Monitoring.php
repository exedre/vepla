<?php

	class Monitoring {
		
		public static function getInvolvedUsersCount($subject) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
				
			$count = 0;	
			if($stmt->prepare("
				select 
					count(distinct(person_id)) as count 
				from 
					participation as p
				inner join 
					element as e
				on 
					p.element_id = e.id
				inner join 
					course as c
				on 
					e.course_id = c.id
				where 
					c.subject_id = ?")) {
				$stmt->bind_param('i', $subject);
				$stmt->bind_result($count);
				$stmt->execute();
				$stmt->fetch();
				$stmt->close();
			}				
			return $count;
		}		
		
		public static function getInvolvedUsersCountOnCourse($courseid) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
				
			$count = 0;	
			if($stmt->prepare("select count(distinct(person_id)) as count from participation where element_id in (select id from element where course_id = ?)")) {
				$stmt->bind_param('i', $courseid);
				$stmt->bind_result($count);
				$stmt->execute();
				$stmt->fetch();
				$stmt->close();
			}				
			return $count;
		}
		
		public static function getTotalLearningTime($subject) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
				
			$time = 0;	
			if($stmt->prepare("
				select 
					sum(p.duration) as time 
				from 
					participation as p
				inner join 
					element as e
				on 
					p.element_id = e.id
				inner join 
					course as c
				on 
					e.course_id = c.id
				where 
					c.subject_id = ?")) {
				$stmt->bind_param('i', $subject);
				$stmt->bind_result($time);
				$stmt->execute();
				$stmt->fetch();
				$stmt->close();
			}				
			return $time;
		}

		public static function getTotalLearningTimeOnCourse($courseid) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
				
			$time = 0;	
			if($stmt->prepare("select sum(duration) as time from participation where element_id in (select id from element where course_id = ?)")) {
				$stmt->bind_param('i', $courseid);
				$stmt->bind_result($time);
				$stmt->execute();
				$stmt->fetch();
				$stmt->close();
			}				
			//return ($time > 0 ? $time : 0);
			return $time;
		}

		
				
		public static function getInvolvedUsers() {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
		
			// $id, $login, $surname, $name, $sex, 
		
			$users = array();
			if($stmt->prepare("
				select 
					p.id, 
					p.login, 					
					p.surname, 
					p.name, 
					p.sex, 
					r.id,
					r.short_description,
					r.long_description
				from 
					person as p  
				inner join
					rank as r 
				on
					p.rank_id = r.id
				where 
					p.id in (
						select 
							distinct(person_id) 
						from 
							participation)
				order by 
					p.surname asc, 
					p.name asc;")) {
				
				$id = 0;
				$login = "";				
				$surname = "";
				$name = "";
				$sex = 0;
				$rank_id = 0; 
				$rank_short = "";
				$rank_long = "";
				$stmt->bind_result($id, $login, $surname, $name, $sex, $rank_id, $rank_short, $rank_long);
				$stmt->execute();
				while($stmt->fetch()) {
					$user = new User($id, $login, $surname, $name, $sex, $rank_id, $rank_short, $rank_long);
					array_push($users, $user);
				}
				$stmt->close();
			}				
			return $users;
		}
		
		public static function getInvolvedUsersOnCourse($courseid) {
		
			$mysql = MySQL::getInstance();
		
			if(!isset($mysql)) die('no database connection: ' . mysqli_connect_error());
					
			// create statement object
			$stmt = $mysql->stmt_init();
		
			// $id, $login, $surname, $name, $sex, 
		
			$users = array();
			if($stmt->prepare("
				select 
					p.id, 
					p.login, 					
					p.surname, 
					p.name, 
					p.sex, 
					r.id,
					r.short_description,
					r.long_description
				from 
					person as p  
				inner join
					rank as r 
				on
					p.rank_id = r.id
				where 
					p.id in (
						select 
							distinct(person_id) 
						from 
							participation
						where course_id = ?	)
				order by 
					p.surname asc, 
					p.name asc;")) {
				$stmt->bind_param('i', $courseid);
				$id = 0;
				$login = "";				
				$surname = "";
				$name = "";
				$sex = 0;
				$rank_id = 0; 
				$rank_short = "";
				$rank_long = "";
				$stmt->bind_result($id, $login, $surname, $name, $sex, $rank_id, $rank_short, $rank_long);
				$stmt->execute();
				while($stmt->fetch()) {
					$user = new User($id, $login, $surname, $name, $sex, $rank_id, $rank_short, $rank_long);
					array_push($users, $user);
				}
				$stmt->close();
			}				
			return $users;
		}
		
		/*
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
				while($stmt->fetch()) {
					$course = new Course($course_id, $course_title, $course_description, $course_url, $course_role_id, $course_role_description);
					array_push($courses, $course);
				}
				$stmt->close();

				
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
		*/
	}
?>
